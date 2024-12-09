<?php

namespace Drupal\surveymanager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserAuthInterface;
use Drupal\user\Entity\User;

use Drupal\Core\Link;


use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Controller for the example route.
 */
class AdminController extends ControllerBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;
  /**
   * Constructs a new AdminController object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }
  /**
   * Creates an instance of the SurveyManagerController.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The Drupal service container.
   *
   * @return \Drupal\surveymanager\Controller\SurveyManagerController
   *   The created instance of the controller.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }
  /**
   * Displays the survey types table.
   *
   * @return array
   *   A render array representing the table.
   */


  /**
   * Returns the content for the example route.
   */

  /**
 * Get a list of the user IDs who have a given role.
 *
 * @param string $role
 *   The ID of the user role to find.
 *
 * @return array
 *   An array of user IDs.
 */
  public function getUsersByRole(string $role):array {
    $query = \Drupal::entityQuery('user');
    $query->accessCheck(FALSE);
    $query->condition('status', 1)
      ->condition('roles', $role);
    return $query->execute();
  }

  function read_user_profile_by_uname($uname) {
    $connection = \Drupal::database();
  
    $query = $connection->select('sm_user_profile', 'up')
      ->fields('up')
      ->condition('up.uname', $uname)
      ->execute();
  
    $results = $query->fetchAll();
  
    return $results;
  }
  
  public function list_users()
  {
    $result=$this->getUsersByRole("surveyor");
    //print_r($result);

    $header = [
      'ID',
      'Name',
      'Mail',
      'User Name',
    ];
    $rows = [];
    foreach ($result as $record) {
      $user = User::load($record[0]);
      $uname=$user->get('name')->value;
      $profile=$this->read_user_profile_by_uname($uname);
      $mail=$user->getEmail();
      $rows[] = [
        'id' => $record[0],
        'name' => $profile[0]->name,
        'mail' => $mail,
        'uname' => $uname,
      ];
    }
      
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function usersurvey_types_list()
  {
    $query = $this->database->select('sm_user_survey_types', 'usrsurtypes')
      ->fields('usrsurtypes');
    $result =$query->execute();

    $header = [
      'ID',
      'Type ID',
      'User Name',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'type_id' => $record->type_id,
        'uname' => $record->uname,
      ];
    }
      
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function recommendations_list()
  {
    $query = $this->database->select('sm_recommendations', 'rec')
      ->fields('rec');
    $result =$query->execute();

    $header = [
      'ID',
      'Job ID',
      'Recommendations',
      'Imposed Date & Time',
      'Due Date & Time',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'job_id' => $record->job_id,
        'recommendation' => $record->recommendation,
        'imposed_on' => $record->imposed_on,
        'due_on' => $record->due_on,
      ];
    }
      
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function job_surveys_list()
  {
    $query = $this->database->select('sm_job_surveys', 'jobsurveys')
      ->fields('jobsurveys');
    $result =$query->execute();

    $header = [
      'ID',
      'Survey Type ID',
      'Job ID',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'type_id' => $record->type_id,
        'job_id' => $record->job_id,
      ];
    }
      
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function job_support_document_types_list()
  {
    $query = $this->database->select('sm_job_support_documents', 'jobsuppdoc')
      ->fields('jobsuppdoc');
    $result =$query->execute();
    
    $header = [
      'ID',
      'Type ID',
      'Job ID',
      'Document URL',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'type_id' => $record->type_id,
        'job_id' => $record->job_id,
        'url' => $record->url,
      ];
    }
  
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function job_certificates_list()
  {
    $query = $this->database->select('sm_job_certificates', 'jobcert')
      ->fields('jobcert');
    $result =$query->execute();

    $header = [
      'ID',
      'Job ID',
      'Certificate ID',
      'Certificate Number',
      'Issued Date',
      'Expiry Date',
      'Certificate URL',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'job_id' => $record->job_id,
        'certificate_id' => $record->certificate_id,
        'number' => $record->number,
        'issued_on' => $record->issued_on,
        'expire_on' => $record->expire_on,
        'url' => $record->url,
      ];
    }
  
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows
      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function document_type_list()
  {
    $query = $this->database->select('sm_support_document_types', 'sdoctype')
      ->fields('sdoctype');
    $result =$query->execute();

    $header = [
      'ID',
      'Type Name',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'type_name' => $record->type_name,
      ];
    }
    
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows
      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function certificate_list()
  {
    $query = $this->database->select('sm_certificates', 'certificates')
      ->fields('certificates');
    $result =$query->execute();
    
    $header = [
      'ID',
      'Certificate Name',
      'Certificate Code',
      'Certificate URL',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'name' => $record->name,
        'code' => $record->code,
        'url' => $record->url,
      ];
    }
    
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows
      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function vessel_list()
  {
    $query = $this->database->select('sm_vessels', 'vessels')
      ->fields('vessels');
    $result =$query->execute();

    $header = [
      'ID',
      'Vessel Name',
      'IMO Number',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'name' => $record->name,
        'imo' => $record->imo,
      ];
    }
  
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows
      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function job_list()
  {
    $query = $this->database->select('sm_jobs', 'jobs')
      ->fields('jobs');
    $result =$query->execute();

    $header = [
      'ID',
      'Job Number',
      'Surveyor User Name',
      'Vessel ID',
      'Status',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'number' => $record->number,
        'surveyor_uname' => $record->surveyor_uname,
        'vessel_id' => $record->vessel_id,
        'status' => $record->status,
      ];
    }
  

    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows
      
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  
  }

  public function survey_types_table()
  {
    $query = $this->database->select('sm_survey_types', 's')
      ->fields('s');
      
    $result =$query->execute();
    //print_r($result);
    $header = [
      'ID',
      'Survey Name',
      'Survey Code',
    ];
    $rows = [];
    foreach ($result as $record) {
      $rows[] = [
        'id' => $record->id,
        'name' => $record->name,
        'code' => $record->code,
      ];
    }
  
    $template= [
      '#theme' => 'surveymanager_jobs_table',
      '#header' => $header,
      '#rows' => $rows
    ];
    $template['#attached']['library'][] = 'surveymanager/main_library';
    return $template;  
  }
  
  public function dashboard() {

     // Create an array to store the buttons.
     $buttons = [];

     // Add a button for the certificate form.
     $certificateUrl = Url::fromRoute('surveymanager.certificate_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Certificate Form'),
       '#url' => $certificateUrl,
       '#attributes' => ['class' => ['button']],
     ];
 
     // Add a button for the document type form.
     $documentTypeUrl = Url::fromRoute('surveymanager.document_type_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Document Type Form'),
       '#url' => $documentTypeUrl,
       '#attributes' => ['class' => ['button']],
     ];
 
     $surveyTypeUrl = Url::fromRoute('surveymanager.survey_type_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Survey Type Form'),
       '#url' => $surveyTypeUrl,
       '#attributes' => ['class' => ['button']],
     ];

     $jobCertificateUrl = Url::fromRoute('surveymanager.job_certificate_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Job Certificate Form'),
       '#url' => $jobCertificateUrl,
       '#attributes' => ['class' => ['button']],
     ];

     $jobsFormUrl = Url::fromRoute('surveymanager.jobs_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Jobs Form'),
       '#url' => $jobsFormUrl,
       '#attributes' => ['class' => ['button']],
     ];

     $jobSupportDocUrl = Url::fromRoute('surveymanager.job_support_documents_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Job Support Documents Form'),
       '#url' => $jobSupportDocUrl,
       '#attributes' => ['class' => ['button']],
     ];

     $jobSurveyUrl = Url::fromRoute('surveymanager.job_survey_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Job Survey Form'),
       '#url' => $jobSurveyUrl,
       '#attributes' => ['class' => ['button']],
     ];

     $vesselsUrl = Url::fromRoute('surveymanager.vessel');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Vessel Form'),
       '#url' => $vesselsUrl,
       '#attributes' => ['class' => ['button']],
     ];

     $userSurveyTypesUrl = Url::fromRoute('surveymanager.user_survey_types_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('User Survey Types Form'),
       '#url' => $userSurveyTypesUrl,
       '#attributes' => ['class' => ['button']],
     ];

     $recommendationsUrl = Url::fromRoute('surveymanager.recommendations_form');
     $buttons[] = [
       '#type' => 'link',
       '#title' => $this->t('Recommendations Form'),
       '#url' => $recommendationsUrl,
       '#attributes' => ['class' => ['button']],
     ];

     // Build the render array for the buttons.
     $build = [
       '#theme' => 'dashboard_page',
       '#buttons' => $buttons,
     ];
 
     return $build;

  }

}



