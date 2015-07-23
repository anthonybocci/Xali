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
        $form = $this->createForm(new SurvivorType(), $survivor);
        $request = $this->get('request');
        $entityManager = $this->getDoctrine()->getManager();
        
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entityManager->persist($survivor);
                $entityManager->flush();
            }
        }
        return $this->render('XaliSurvivorBundle:Management:add_survivor.html.twig',
                array('form' => $form->createView()));
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
