<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Implements the Dashboard form.
 */
class DashboardForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dashboard_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['links'] = [
      '#type' => 'item',
      '#title' => $this->t('Dashboard Links'),
      '#markup' => '<ul>' .
        '<li>' . $this->l($this->t('Login'), Url::fromRoute('surveymanager.login_form')) . '</li>' .
        '<li>' . $this->l($this->t('User Profile'), Url::fromRoute('surveymanager.admin_list_users')) . '</li>' .
        '<li>' . $this->l($this->t('Add Jobs'), Url::fromRoute('surveymanager.jobs_form')) . '</li>' .
        '<li>' . $this->l($this->t('Job Survey'), Url::fromRoute('surveymanager.job_survey_form')) . '</li>' .
        '<li>' . $this->l($this->t('Job Support Documents'), Url::fromRoute('surveymanager.job_support_documents_form')) . '</li>' .
        '<li>' . $this->l($this->t('Job Certificates'), Url::fromRoute('surveymanager.job_certificate_form')) . '</li>' .
        '<li>' . $this->l($this->t('Recommendation'), Url::fromRoute('surveymanager.recommendations_form')) . '</li>' .
        '<li>' . $this->l($this->t('Support Document Types'), Url::fromRoute('surveymanager.job_support_documents_form')) . '</li>' .
        '<li>' . $this->l($this->t('Vessels'), Url::fromRoute('surveymanager.vessel')) . '</li>' .
        '<li>' . $this->l($this->t('User Survey Types'), Url::fromRoute('surveymanager.user_survey_types_form')) . '</li>' .
        '<li>' . $this->l($this->t('Certificates'), Url::fromRoute('surveymanager.certificate_form')) . '</li>' .
        '</ul>',
    ];

    $form['back'] = [
      '#type' => 'link',
      '#title' => $this->t('Back'),
      '#url' => Url::fromRoute('<front>'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // No form submission logic is needed for this form.
  }

}
