<?php

namespace Drupal\surveymanager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\UserAuthInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;


use Drupal\Core\Routing\RouteMatchInterface;


/**
 * User login form.
 */
class LoginForm extends FormBase {

  /**
   * The user authentication service.
   *
   * @var \Drupal\user\UserAuthInterface
   */
  protected $userAuth;

  /**
   * Constructs a UserLoginForm instance.
   *
   * @param \Drupal\user\UserAuthInterface $user_auth
   *   The user authentication service.
   */
  public function __construct(UserAuthInterface $user_auth) {
    $this->userAuth = $user_auth;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('user.auth')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'surveymanager_login_form';
  }

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

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('name');
    $password = $form_state->getValue('pass');

    $uid = $this->userAuth->authenticate($username, $password);

    if ($uid) {
      $user = User::load($uid);
      user_login_finalize($user);

      // Check if the user has the 'surveyadmin' role.
      if ($user->hasRole('survey_admin')) {
        //To redirect to admin dashboard if logined as Survey Administrator
        $form_state->setRedirectUrl(Url::fromRoute('surveymanager.admin_dashboard'));
      }
      else {
        // Redirect to a different page if the user is not a surveyadmin.
        $form_state->setRedirectUrl(Url::fromRoute('surveymanager.admin_list_users'));
      }
    }
    else {
      \Drupal::messenger()->addError(t('Invalid credentials'));
    }
  }

}
