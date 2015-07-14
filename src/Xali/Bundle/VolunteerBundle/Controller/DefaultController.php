<?php

namespace Xali\Bundle\VolunteerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('XaliVolunteerBundle:Default:index.html.twig', array('name' => $name));
    }
}
