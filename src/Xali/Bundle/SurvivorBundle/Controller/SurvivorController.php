<?php

namespace Xali\Bundle\SurvivorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\SurvivorBundle\Form\SurvivorType;
use Xali\Bundle\SurvivorBundle\Entity\Survivor;

/**
 * Manage survivors
 * 
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class SurvivorController extends Controller
{
    /**
     * Add a survivor
     */
    public function add_survivorAction()
    {
        $survivor = new Survivor();
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
    


}
