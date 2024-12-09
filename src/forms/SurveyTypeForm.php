<?php
    
    namespace Drupal\surveymanager\Form;

    use Drupal\Core\Form\FormBase;
    use Drupal\Core\Form\FormStateInterface;
    use Drupal\Core\Database\Database;
    use Drupal\Component\Utility\Unicode;

    class SurveyTypeForm extends FormBase {

    public function getFormId() {
        return 'sm_survey_type_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Survey Name'),
            '#required' => TRUE,
        ];

        $form['code'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Survey Code'),
            '#required' => TRUE,
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Add'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        
        //To get data from the form.
        $name = $form_state->getValue('name');
        $code = $form_state->getValue('code');

        // Convert the code to uppercase.
        $code = strtoupper($code);

        // Convert the name to title case.
        $name = Unicode::ucwords($name);

        $values = [
            'name' => $name,
            'code' => $code,
        ];

        // Insert the values into the database.
        \Drupal::database()
            ->insert('sm_survey_types')
            ->fields($values)
            ->execute();

        // Set a success message.
        \Drupal::messenger()
            ->addStatus($this->t('Survey Type has been added.'));

        // Redirect to a different page after the form submission.
        $form_state->setRedirect('surveymanager.list_survey_types');
    }

}