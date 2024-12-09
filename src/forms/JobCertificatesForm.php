<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;

/**
 * Job Certificates form.
 */
class JobCertificatesForm extends FormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * JobCertificatesForm constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, MessengerInterface $messenger, Connection $database) {
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('messenger'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'sm_job_certificates_form';
  }

  /**
   * Retrieves job options for the select field.
   *
   * @return array
   *   An array of job options.
   */
  private function getJobOptions() {
    $jobRecords = $this->database->select('sm_jobs', 'j')
      ->fields('j', ['id', 'number', 'surveyor_uname', 'vessel_id'])
      ->execute()
      ->fetchAll();

    $jobOptions = [];
    foreach ($jobRecords as $job) {
      $jobOptions[$job->id] = $job->number . ' (Surveyor: ' . $job->surveyor_uname . ', Vessel ID: ' . $job->vessel_id . ')';
    }

    return $jobOptions;
  }

  /**
   * Retrieves certificate options for the select field.
   *
   * @return array
   *   An array of certificate options.
   */
  private function getCertificateOptions() {
    $certificateRecords = $this->database->select('sm_certificates', 'c')
      ->fields('c', ['id', 'name', 'code'])
      ->execute()
      ->fetchAll();

    $certificateOptions = [];
    foreach ($certificateRecords as $certificate) {
      $certificateOptions[$certificate->id] = $certificate->name . ' (' . $certificate->code . ')';
    }

    return $certificateOptions;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['job_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Job ID'),
      '#options' => $this->getJobOptions(),
      '#required' => TRUE,
    ];

    $form['certificate_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Certificate ID'),
      '#options' => $this->getCertificateOptions(),
      '#required' => TRUE,
    ];

    $form['number'] = [
      '#type' => 'number',
      '#title' => $this->t('Certificate Number'),
      '#required' => TRUE,
    ];

    $form['issued_on'] = [
      '#type' => 'date',
      '#title' => $this->t('Issued Date'),
      '#required' => TRUE,
    ];

    $form['expire_on'] = [
      '#type' => 'date',
      '#title' => $this->t('Expiry Date'),
      '#required' => TRUE,
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Certificate URL'),
      '#maxlength' => 64,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $jobId = $form_state->getValue('job_id');
    $certificateId = $form_state->getValue('certificate_id');
    $number = $form_state->getValue('number');
    $issuedOn = $form_state->getValue('issued_on');
    $expireOn = $form_state->getValue('expire_on');
    $url = $form_state->getValue('url');

    // Insert the form values into the 'sm_job_certificates' table.
    $this->database->insert('sm_job_certificates')
      ->fields([
        'job_id' => $jobId,
        'certificate_id' => $certificateId,
        'number' => $number,
        'issued_on' => $issuedOn,
        'expire_on' => $expireOn,
        'url' => $url,
      ])
      ->execute();

    $this->messenger->addMessage($this->t('Form submitted successfully.'));

    // To redirect to another site to list job certificates.
    $form_state->setRedirectUrl(Url::fromRoute('surveymanager.list_job_certificates'));
  }

}
