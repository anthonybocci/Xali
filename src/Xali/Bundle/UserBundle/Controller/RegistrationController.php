<?php

namespace Xali\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\UserBundle\Form\UserType;

/**
 * Controller managing the registration
 *
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class RegistrationController extends Controller
{
    public function registerAction()
    {
        return $this->render('XaliUserBundle:Registration:register.html.twig');        
    }

   
}
