<?php

namespace Xali\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Controller managing the user login
 *
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class SecurityController extends Controller
{
    /**
     * Log in the user
     */
    public function loginAction()
    {
        
        $request = $this->getRequest();
	$session = $request->getSession();

	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
		$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
	} else {
		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
	}

	$params = array(
		"last_username" => $session->get(SecurityContext::LAST_USERNAME),
		"error"         => $error,
	);

        return $this->render('XaliUserBundle:Security:login.html.twig', $params);
    }
    
    public function check()
    {
	throw new \RuntimeException('You must configure the check path to be '
                . 'handled by the firewall using form_login in your security '
                . 'firewall configuration.');
    }


    public function logout()
    {
	throw new \RuntimeException('You must activate the logout in your '
                . 'security firewall configuration.');
    }
    
}
    