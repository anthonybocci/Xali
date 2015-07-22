<?php

namespace Xali\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        return $this->render('XaliUserBundle:Security:login.html.twig');
    }
    
}
    