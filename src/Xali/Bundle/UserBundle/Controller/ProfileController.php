<?php

namespace Xali\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller managing the user profile
 *
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class ProfileController extends Controller
{
    /**
     * Show the user
     */
    public function showAction()
    {
        return $this->render('XaliUserBundle:Profile:show.html.twig');
    }

    /**
     * Edit the user
     */
    public function editAction()
    {

        return $this->render('XaliUserBundle:Profile:edit.html.twig');
    }
}
