<?php
namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;


class LoginForm extends FormBase {
/**
   * {@inheritdoc}
 */
  
   public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#required' => TRUE,
    ];

    $form['pass'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Log in'),
    ];
    return $form;
  }
}

?>