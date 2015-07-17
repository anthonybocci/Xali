<?php

namespace Xali\Bundle\SurvivorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SurvivorController extends Controller
{
    public function add_survivorAction()
    {
        return $this->render('XaliSurvivorBundle:Management:add_survivor.html.twig');
    }
    
    public function see_profileAction()
    {
        return $this->render('XaliSurvivorBundle:Profile:profile.html.twig');
    }
    
    public function searchAction()
    {
        return $this->render('XaliSurvivorBundle:Search:search.html.twig');
    }
    
    public function search_resultAction()
    {
        return $this->render('XaliSurvivorBundle:Search:search_result.html.twig');
    }

}
