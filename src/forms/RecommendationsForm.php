<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\UserAuthInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements the SM Recommendations Form.
 */
class recommendationsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sm_recommendations_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $jobOptions = $this->getJobOptions();

    $form['job_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Job ID'),
      '#required' => TRUE,
      '#options' => $jobOptions,
    ];

    $form['recommendation'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Recommendation'),
      '#maxlength' => 64,
    ];

    $form['imposed_on'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Imposed Date and Time'),
      '#maxlength' => 255,
    ];

    $form['due_on'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Due Date and Time'),
      '#maxlength' => 255,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Add any form validation if required.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Process the form submission.
    // Retrieve the values from $form_state->getValue() and save them to the database.
    // Retrieve the values from the form submission.
    $jobId = $form_state->getValue('job_id');
    $recommendation = $form_state->getValue('recommendation');
    $imposedOn = $form_state->getValue('imposed_on');
    $dueOn = $form_state->getValue('due_on');

    // Save the form data to the 'sm_recommendations' table in the database.
    $values = [
      'job_id' => $jobId,
      'recommendation' => $recommendation,
      'imposed_on' => $imposedOn,
      'due_on' => $dueOn,
    ];

    // Insert the values into the 'sm_recommendations' table.
    $query = \Drupal::database()->insert('sm_recommendations')
      ->fields($values)
      ->execute();

    if ($query) {
      // Form submission success message.
      \Drupal::messenger()->addMessage($this->t('Form submitted successfully.'));

      //To redirect to another site to display list of job support documents.
      $form_state->setRedirectUrl(Url::fromRoute('surveymanager.list_recommendations'));
    } else {
      // Form submission error message.
      \Drupal::messenger()->addError($this->t('Form submission failed.'));
    }
  }

  /**
   * Get the job options for the dropdown list.
   *
   * @return array
   *   An array of job options in the format [value => label].
   */
  private function getJobOptions() {
    $jobOptions = [];

    // Retrieve the job data from the sm_jobs table.
    $query = \Drupal::database()->select('sm_jobs', 'j');
    $query->fields('j', ['id', 'number', 'surveyor_uname', 'vessel_id']);
    $results = $query->execute()->fetchAll();

    // Build the options array.
    foreach ($results as $result) {
      $jobOptions[$result->id] = $result->number . ' (Surveyor: ' . $result->surveyor_uname . ' , Vessel ID: ' . $result->vessel_id.')';
    }

    return $jobOptions;
  }
}
