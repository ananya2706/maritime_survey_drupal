<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class JobSupportDocumentsForm extends FormBase {

  public function getFormId() {
    return 'job_support_documents_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve support document types from 'sm_support_document_types' table.
    $connection = Database::getConnection();
    $support_document_types = $connection->select('sm_support_document_types', 's')
      ->fields('s', ['id', 'type_name'])
      ->execute()
      ->fetchAll();

    $support_document_type_options = [];
    foreach ($support_document_types as $support_document_type) {
      $support_document_type_options[$support_document_type->id] = $support_document_type->type_name;
    }

    // Retrieve job details from 'sm_jobs' table.
    $job_options = $connection->select('sm_jobs', 'j')
      ->fields('j', ['number', 'surveyor_uname', 'vessel_id'])
      ->execute()
      ->fetchAll();

    $job_dropdown_options = [];
    foreach ($job_options as $job) {
      $job_dropdown_options[$job->number] = $job->number . ' (Surveyor: ' . $job->surveyor_uname . ', Vessel ID: ' . $job->vessel_id . ')';
    }

    $form['type_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Support Document Type'),
      '#options' => $support_document_type_options,
      '#required' => TRUE,
    ];

    $form['job_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Job'),
      '#options' => $job_dropdown_options,
      '#required' => TRUE,
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Document URL'),
      '#required' => FALSE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Perform any form validation if required.
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save form data to the database.
    $type_id = $form_state->getValue('type_id');
    $job_id = $form_state->getValue('job_id');
    $url = $form_state->getValue('url');

    $database = Database::getConnection();
    $database->insert('sm_job_support_documents')
      ->fields([
        'type_id' => $type_id,
        'job_id' => $job_id,
        'url' => $url,
      ])
      ->execute();

    // Provide a success message and redirect to a specific page if needed.
    $messenger = \Drupal::messenger();
    $messenger->addMessage($this->t('Job support document saved successfully.'));
  
    // To redirect to another site to display a list of job support document types.
    $form_state->setRedirectUrl(Url::fromRoute('surveymanager.list_job_support_document_types'));
  }

}
