<?php

namespace Xali\Bundle\CampBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CampController extends Controller
{
    public function add_campAction()
    {
        return $this->render('XaliCampBundle:Management:add_camp.html.twig');
    }
    
    public function profileAction()
    {
        return $this->render('XaliCampBundle:Profile:profile.html.twig');
    }
    
    public function assign_volunteerAction()
    {
        return $this->render('XaliCampBundle:Management:assign_volunteer.html.twig');
    }
}
