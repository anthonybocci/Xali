<?php

namespace Xali\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * display Xali's index
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('XaliDefaultBundle:Default:index.html.twig');
    }
}
