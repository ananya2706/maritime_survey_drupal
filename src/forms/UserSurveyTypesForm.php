<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * User Survey Types form.
 */
class UserSurveyTypesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'user_survey_types_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $surveyTypeOptions = $this->getSurveyTypeOptions();

    $form['type_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Type ID'),
      '#required' => TRUE,
      '#options' => $surveyTypeOptions,
    ];

    $form['uname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User Name'),
      '#maxlength' => 64,
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
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // Add any form validation if required.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Retrieve the values from the form submission.
    $typeId = $form_state->getValue('type_id');
    $userName = $form_state->getValue('uname');

    // Save the form data to the 'sm_user_survey_types' table in the database.
    $connection = \Drupal::database();
    $query = $connection->insert('sm_user_survey_types')
      ->fields(['type_id', 'uname'])
      ->values([$typeId, $userName])
      ->execute();

    if ($query) {
      // Form submission success message.
      \Drupal::messenger()->addMessage($this->t('Form submitted successfully.'));

      // To redirect to another site to display a list of job support documents.
      $form_state->setRedirectUrl(Url::fromRoute('surveymanager.list_user_survey_types'));
    } else {
      // Form submission error message.
      \Drupal::messenger()->addError($this->t('Form submission failed.'));
    }
  }

  /**
   * Get the survey type options for the dropdown list.
   *
   * @return array
   *   An array of survey type options in the format [value => label].
   */
  private function getSurveyTypeOptions() {
    $surveyTypeOptions = [];

    // Retrieve the survey type data from the 'sm_survey_types' table.
    $query = \Drupal::database()->select('sm_survey_types', 's');
    $query->fields('s', ['id', 'name', 'code']);
    $results = $query->execute()->fetchAll();

    // Build the options array.
    foreach ($results as $result) {
      $surveyTypeOptions[$result->id] = $result->name . ' (' . $result->code.')';
    }

    return $surveyTypeOptions;
  }
}
