<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

class CertificateForm extends FormBase {

  public function getFormId() {
    return 'certificate_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Certificate Name'),
      '#required' => TRUE,
    ];

    $form['code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Certificate Code'),
      '#required' => TRUE,
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Certificate Template URL'),
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
    $name = $form_state->getValue('name');
    $code = $form_state->getValue('code');
    $url = $form_state->getValue('url');

    $database = Database::getConnection();
    $database->insert('sm_certificates')
      ->fields([
        'name' => $name,
        'code' => $code,
        'url' => $url,
      ])
      ->execute();

      
    // Display a success message.
    \Drupal::messenger()->addMessage('Certificate has been added.', 'status');

    $form_state->setRedirectUrl(Url::fromRoute('surveymanager.list_certificate'));
  }

}
?>
