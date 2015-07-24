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
     * 
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function add_survivorAction()
    {
        $survivor = new Survivor();
        $form = $this->createForm(new SurvivorType(), $survivor);
        $request = $this->get('request');
        $entityManager = $this->getDoctrine()->getManager();
        $converter = $this->container->get('xali_survivor.converter');
        
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->convertWeightAndHeight($survivor, $converter);
                $entityManager->persist($survivor);
                $entityManager->flush();
            }
        }
        return $this->render('XaliSurvivorBundle:Management:add_survivor.html.twig',
                array('form' => $form->createView()));
    }
    
    /**
     * Convert weight and height if necessary
     * 
     * @param \Xali\Bundle\SurvivorBundle\Entity\Survivor $survivor
     * @param \Xali\Bundle\SurvivorBundle\Converter\Converter $converter
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    private function convertWeightAndHeight($survivor, $converter)
    {
        //If weight unit is kilogrammes
        if ($survivor->getWeightUnit() == "kg") {
            $survivor->setWeight($converter->fromKgToLb($survivor->getWeight()));
        }
        //If height unit is centimeters
        if ($survivor->getHeightUnit() == "cm") {
            $survivor->setHeight($converter->fromCmToInch($survivor->getHeight()));
        }
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
