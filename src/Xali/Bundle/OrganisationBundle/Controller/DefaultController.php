<?php

namespace Xali\Bundle\OrganisationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('XaliOrganisationBundle:Default:index.html.twig', array('name' => $name));
    }
}
