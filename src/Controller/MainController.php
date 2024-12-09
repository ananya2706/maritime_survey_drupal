<?php

namespace Drupal\surveymanager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Entity\EntityTypeManagerInterface;
/**
 * Controller for the example route.
 */
class MainController extends ControllerBase {

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
  
  public function main()
  {

    $user=$this->currentUser();
    if (!$user->isAuthenticated()) {
          
      $loginUrl = Url::fromRoute('surveymanager.login_form');
      $response = new RedirectResponse($loginUrl->toString());
      return $response;
    }
    else {

      // Redirect according to the user roles  
      if (in_array('surveyor', $user->getRoles())) {
            $loginUrl = Url::fromRoute('surveymanager.surveyor_dashboard');
            $response = new RedirectResponse($loginUrl->toString());
            return $response;
        
        }
        else if (in_array('survey_admin', $user->getRoles())) {
            $loginUrl = Url::fromRoute('surveymanager.admin_dashboard');
            $response = new RedirectResponse($loginUrl->toString());
            //$response->send();
            //return;
            return $response;
        }
    }

    
    return;
  }    

}
