<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 7/22/15
 * Time: 10:21 AM
 */

namespace EffectiveSolutions\SecurityBundle\Controller;


use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'EffectiveSolutionsSecurityBundle:Login:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }
}