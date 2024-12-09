<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Provides a Vessels form.
 */
class VesselsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'your_module_vessels_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['vessel_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Vessel Name'),
      '#required' => TRUE,
    ];

    $form['imo_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('IMO Number'),
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
    // Save the submitted form data to the 'sm_vessels' table.
    $connection = Database::getConnection();
    $fields = [
      'name' => $form_state->getValue('vessel_name'),
      'imo' => $form_state->getValue('imo_number'),
    ];
    $connection->insert('sm_vessels')
      ->fields($fields)
      ->execute();

    // Provide a success message.
    $this->messenger()->addMessage($this->t('Vessel details have been saved successfully.'));

    $form_state->setRedirectUrl(Url::fromRoute('surveymanager.list_vessels'));
  }

}
