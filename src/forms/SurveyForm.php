<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\UserAuthInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SurveyForm extends FormBase {

  public function getFormId() {
    return 'survey_form';
  }
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Fetch survey types from the database
    $database = \Drupal::database();
    $query = $database->select('sm_survey_types', 's');
    $query->fields('s', ['id', 'name']);
    $results = $query->execute()->fetchAll();
  
  
  $results = $query->execute()->fetchAll();

    $options = [];
    foreach ($results as $result) {
      $options[$result->id] = $result->name;
    }
    $surveyCodes = [
            'SSH' => 'Special Survey Hull',
            'SSM' => 'Special Survey Machinery',
            'CLA' => 'Class Annual Survey',
            'CLI' => 'Class Intermediate Survey',
            'CLB' => 'Bottom Survey',
            'Tailshaft' => 'Tailshaft Survey (Identification)',
            'BLR' => 'Boiler Survey',
            'IGS' => 'Survey of Inert Gas System',
            'COW' => 'Survey of Crude Oil Washing System',
            'UMS' => 'Survey of Unmanned Machinery Spaces',
            'UTG' => 'Thickness Gauging',
            'SEI' => 'Cargo Ship Safety Equipment Initial Survey',
            'SEA' => 'Cargo Ship Safety Equipment Annual Survey',
            'SEP' => 'Cargo Ship Safety Equipment Periodical Survey',
            'SER' => 'Cargo Ship Safety Equipment Renewal Survey',
            'SCI' => 'Cargo Ship Safety Construction Initial Survey',
            'SCA' => 'Cargo Ship Safety Construction Annual Survey',
            'SCN' => 'Cargo Ship Safety Construction Intermediate Survey',
            'SCR' => 'Cargo Ship Safety Construction Renewal Survey',
            'SRI' => 'Cargo Ship Safety Radio Initial Survey',
            'SRP' => 'Cargo Ship Safety Radio Periodical Survey',
            'SRR' => 'Cargo Ship Safety Radio Renewal Survey',
            'PSI' => 'Passenger Ship Safety Initial Survey',
            'PSR' => 'Passenger Ship Safety Renewal Survey',
            'LLI' => 'International Load Line Initial Survey',
            'LLA' => 'International Load Line Annual Survey',
            'LLR' => 'International Load Line Renewal Survey',
            'IOI' => 'International Oil Pollution Prevention Initial Survey',
            'IOA' => 'International Oil Pollution Prevention Annual Survey',
            'ION' => 'International Oil Pollution Prevention Intermediate Survey',
            'IOR' => 'International Oil Pollution Prevention Renewal Survey',
            'NLI' => 'International NLS Pollution Prevention Initial Survey',
            'NLA' => 'International NLS Pollution Prevention Annual Survey',
            'NLN' => 'International NLS Pollution Prevention Intermediate Survey',
            'NLR' => 'International NLS Pollution Prevention Renewal Survey',
            'SPI' => 'International Sewage Pollution Prevention Initial Survey',
            'SPR' => 'International Sewage Pollution Prevention Renewal Survey',
            'GPI' => 'Garbage Pollution Prevention Initial Survey',
            'GPA' => 'Garbage Pollution Prevention Annual Survey',
            'GPR' => 'Garbage Pollution Prevention Renewal Survey',
            'EAP' => 'Engine International Air Pollution Prevention Survey',
            'IAI' => 'International Air Pollution Prevention Initial Survey',
            'IAA' => 'International Air Pollution Prevention Annual Survey',
            'IAN' => 'International Air Pollution Prevention Intermediate Survey',
            'IAR' => 'International Air Pollution Prevention Renewal Survey',
            'BWI' => 'International Ballast Water Management Initial Survey',
            'BWA' => 'International Ballast Water Management Annual Survey',
            'BWN' => 'International Ballast Water Management Intermediate Survey',
            'BWR' => 'International Ballast Water Management Renewal Survey',
            'BCI' => 'Dangerous Chemicals in Bulk Initial Survey',
            'BCA' => 'Dangerous Chemicals in Bulk Annual Survey',
            'BCN' => 'Dangerous Chemicals in Bulk Intermediate Survey',
            'BCR' => 'Dangerous Chemicals in Bulk Renewal'
        
      
        // Add more survey codes and names here...
      ];
      
      // Get user input letters
  $user_input = $form_state->getValue('user_input');
  
  // Filter the survey types based on user input
  $filtered_types = [];
  if (!empty($user_input)) {
    $user_input = strtolower($user_input); // Convert input to lowercase for case-insensitive matching
    
    foreach ($surveyCodes as $code => $name) {
      $code_lower = strtolower($code);
      $name_lower = strtolower($name);
      
      if (strpos($code_lower, $user_input) !== false || strpos($name_lower, $user_input) !== false) {
        $filtered_types[$code] = $name;
      }
    }
  } else {
    $filtered_types = $surveyCodes; // No filter applied, show all survey types
  }
  
    // Add form element
    $form['survey_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Survey Type'),
      '#options' => $surveyCodes,
      '#required' => TRUE,
    ];
    
  
    // Add other form elements as needed
  
    // Add submit button
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
  
    return $form;
  }
  

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Handle form submission
    // You can access the selected survey type using $form_state->getValue('survey_type');
    // Perform any necessary operations here
    // Example: Store the selected survey type in the database or display a message
    \Drupal::messenger()->addMessage($this->t('Survey type selected: @survey_type', ['@survey_type' => $form_state->getValue('survey_type')]));
  }

}