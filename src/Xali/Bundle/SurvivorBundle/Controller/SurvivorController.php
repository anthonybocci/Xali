<?php

namespace Xali\Bundle\SurvivorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\SurvivorBundle\Form\SurvivorType;
use Xali\Bundle\SurvivorBundle\Entity\Survivor;
use Xali\Bundle\CampBundle\Entity\Camp;

/**
 * Manage survivors
 * 
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class SurvivorController extends Controller
{
    /**
     * Add a survivor into a camp
     * 
     * @param Xali\Bundle\CampBundle\Entity\Camp $camp the camp the survivor
     * belong to
     * @param integer $survivor_id sur survivor's id
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     * @throws createAccessDeniedException
     * @throws createNotFoundException
     */
    public function add_survivorAction(Camp $camp, $survivor_id)
    {
        $user = $this->getUser();
        //If volunteer try to add a survivor in an other camp
        if ($user->getCamp() == null || $user->getCamp()->getId() != 
                                                               $camp->getId()) {
            throw $this->createAccessDeniedException();
        }
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        $converter = $this->container->get('xali_survivor.converter');
        $survivorClass = 'XaliSurvivorBundle:Survivor';
        $givenSurvivor = $em->getRepository($survivorClass)->find($survivor_id);
        //If no survivor is found, add a new. Else, take the found survivor
        $survivor = (empty($givenSurvivor)) ? new Survivor() : $givenSurvivor;
        //On survivor update, if survivor doesn't belong to the given camp
        if ($survivor_id != 0 && $survivor->getId() == null || 
                            $survivor->getCamp()->getId() != $camp->getId()) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(new SurvivorType(), $survivor);
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->convertWeightAndHeight($survivor, $converter);
                $survivor->setCamp($camp);
                $em->persist($survivor);
                $em->flush();
            }
        }
        $render = 'XaliSurvivorBundle:Management:add_survivor.html.twig';
        return $this->render($render, array('form' => $form->createView(), 
                                                            'camp' => $camp,
                                                    'survivor' => $survivor));
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
    
    /**
     * Show survivor's profile
     * 
     * @param integer $id the survivor's id
     * @throws createNotFoundException
     */
    public function see_profileAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $survivorRepo = $em->getRepository('XaliSurvivorBundle:Survivor');
        $survivor = $survivorRepo->findWithCamp($id);
        $session = $this->get('session');
        if (!($survivor instanceof Survivor)) {
            throw $this->createNotFoundException();
        }
        //If survivor hasn't camp
        if ($survivor->getCamp() == null) {
            $session->set('csrf_token_assign_camp', sha1(time() * rand()));
        } else {
            $session->set('csrf_token_leave_camp', sha1(time() * rand()));
        }
        
        
        return $this->render('XaliSurvivorBundle:Profile:profile.html.twig',
                array(
                    'survivor' => $survivor
                ));
    }
    
    public function leave_campAction($survivor_id, $camp_id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $survivorRepo = $em->getRepository('XaliSurvivorBundle:Survivor');
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $camp = $campRepo->find($camp_id);
        $survivor = $survivorRepo->findWithCamp($survivor_id);
        $session = $this->get('session');
        $request = $this->get('request');
        //If camp doesn't exist
        if (!($camp instanceof Camp) || !($survivor instanceof Survivor)) {
            throw $this->createNotFoundException();
        }
        //If volunteer try to manage a survivor in an other camp
        if ($user->getCamp() == null || $user->getCamp()->getId() != 
                                                               $camp->getId()) {
            throw $this->createAccessDeniedException();
        }
        //If survivor doesn't belong to this camp
        if ($camp->getId() != $survivor->getCamp()->getId()) {
            throw $this->createNotFoundException();
        }
        $token = $session->get('csrf_token_leave_camp');
        $givenToken = $request->request->get('csrf_token_leave_camp');
        //If tokens are invalids
        if (empty($token) || empty($givenToken) || $token != $givenToken) {
            throw $this->createAccessDeniedException();
        }
        $survivor->setCamp(null);
        $em->flush();
        return $this->redirect($this->generateUrl('xali_survivor_profile_profile',
                array(
                    'id' => $survivor->getId()
                )));
    }
    
    
    public function assign_campAction($survivor_id, $camp_id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $survivorRepo = $em->getRepository('XaliSurvivorBundle:Survivor');
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $camp = $campRepo->find($camp_id);
        $survivor = $survivorRepo->findWithCamp($survivor_id);
        $session = $this->get('session');
        $request = $this->get('request');
        //If camp doesn't exist
        if (!($camp instanceof Camp) || !($survivor instanceof Survivor)) {
            throw $this->createNotFoundException();
        }
        //If volunteer try to manage a survivor in an other camp
        if ($user->getCamp() == null || $user->getCamp()->getId() != 
                                                               $camp->getId()) {
            throw $this->createAccessDeniedException();
        }
        //If survivor belong to a camp
        if ($survivor->getCamp() != null) {
            throw $this->createAccessDeniedException();
        }
        $token = $session->get('csrf_token_assign_camp');
        $givenToken = $request->request->get('csrf_token_assign_camp');
        //If tokens are invalids
        if (empty($token) || empty($givenToken) || $token != $givenToken) {
            throw $this->createAccessDeniedException();
        }
        $survivor->setCamp($camp);
        $em->flush();
        return $this->redirect($this->generateUrl('xali_survivor_profile_profile',
                array(
                    'id' => $survivor->getId()
                )));
    }
    
    public function searchAction()
    {
        return $this->render('XaliSurvivorBundle:Search:search.html.twig');
    }
    


}
