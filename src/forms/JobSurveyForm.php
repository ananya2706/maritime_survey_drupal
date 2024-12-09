<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class JobSurveyForm extends FormBase {

  public function getFormId() {
    return 'job_survey_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve survey types from 'sm_survey_types' table.
    $connection = Database::getConnection();
    $survey_types = $connection->select('sm_survey_types', 's')
      ->fields('s', ['id', 'name', 'code'])
      ->execute()
      ->fetchAll();

    $survey_type_options = [];
    foreach ($survey_types as $survey_type) {
      $survey_type_options[$survey_type->id] = $survey_type->name . ' (' . $survey_type->code . ')';
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
      '#title' => $this->t('Survey Type'),
      '#options' => $survey_type_options,
      '#required' => TRUE,
    ];

    $form['job_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Job'),
      '#options' => $job_dropdown_options,
      '#required' => TRUE,
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

    $database = Database::getConnection();
    $database->insert('sm_job_surveys')
      ->fields([
        'type_id' => $type_id,
        'job_id' => $job_id,
      ])
      ->execute();

    // Provide a success message and redirect to a specific page if needed.
    $messenger = \Drupal::messenger();
    $messenger->addMessage($this->t('Job survey saved successfully.'));
  
    //To redirect to another site to display a list of job support documents.
    $form_state->setRedirectUrl(Url::fromRoute('surveymanager.list_job_surveys'));
  }

}
