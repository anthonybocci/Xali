<?php

namespace Xali\Bundle\SurvivorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('XaliSurvivorBundle:Default:index.html.twig', array('name' => $name));
    }
}
