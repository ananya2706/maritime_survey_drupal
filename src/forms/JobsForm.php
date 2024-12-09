<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Provides a job details form.
 */
class JobsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'my_module_jobs_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve vessel details from the 'sm_vessels' table.
    $connection = Database::getConnection();
    $vessel_options = $connection->select('sm_vessels', 'v')
      ->fields('v', ['id', 'name', 'imo'])
      ->execute()
      ->fetchAll();

    $options = [];
    foreach ($vessel_options as $vessel) {
      $options[$vessel->id] = $vessel->name . ' (' . $vessel->imo . ')';
    }

    $form['number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Job number'),
      '#required' => TRUE,
    ];

    $form['surveyor_uname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surveyor username'),
    ];

    $form['vessel_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Vessel'),
      '#options' => $options,
      '#required' => TRUE,
    ];

    $form['status'] = [
      '#type' => 'select',
      '#title' => $this->t('Status'),
      '#options' => [
        'active' => $this->t('Active'),
        'pending' => $this->t('Pending'),
      ],
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Add any form validation if needed.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the submitted form data to the 'sm_jobs' table.
    $connection = Database::getConnection();
    $fields = [
      'number' => $form_state->getValue('number'),
      'surveyor_uname' => $form_state->getValue('surveyor_uname'),
      'vessel_id' => $form_state->getValue('vessel_id'),
      'status' => $form_state->getValue('status'),
    ];
    $connection->insert('sm_jobs')
      ->fields($fields)
      ->execute();

    // Provide a success message and redirect to a specific page if needed.
    $messenger = \Drupal::messenger();
    $messenger->addMessage($this->t('The job has been submitted successfully.'));

    $form_state->setRedirectUrl(Url::fromRoute('surveymanger.list_jobs'));
  }

}
