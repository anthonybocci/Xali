<?php

namespace Xali\Bundle\SurvivorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\SurvivorBundle\Form\SurvivorType;
use Xali\Bundle\SurvivorBundle\Entity\Survivor;
use Xali\Bundle\CampBundle\Entity\Camp;
use Xali\Bundle\UserBundle\RightManager\XaliRightsManager;
use Xali\Bundle\SurvivorBundle\Form\SurvivorSearchType;

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
        /**
         * @var \Xali\Bundle\UserBundle\RightsManager\XaliRightsManager
         * Service which manager rights
         */
        $rightsManager = $this->get('xali_user.rightsmanager');
        $request = $this->get('request');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $converter = $this->container->get('xali_survivor.converter');
        $survivorClass = 'XaliSurvivorBundle:Survivor';
        $survivor = null;
        
        //If volunteer try to add a survivor in an other camp and is not root
        if (!$rightsManager->userBelongsToCamp($user, $camp) &&
                !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            throw $this->createAccessDeniedException();
        }
        //If no survivor is given create a new
        if ($survivor_id == 0) {
            $survivor = new Survivor();
        }else {
            $survivor = $em->getRepository($survivorClass)->find($survivor_id);
        }
        /*On update if survivor doesn't exist  or survivor doesn't belong to this
         * camp
         */
        if (!($survivor instanceof Survivor || 
                !$rightsManager->survivorBelongToCamp($survivor, $camp))) {
            throw $this->createNotFoundException();
        }
       
        $form = $this->createForm(new SurvivorType(), $survivor);
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->convertWeightAndHeight($survivor, $converter);
                /* Put firstname and lastname in lowercase, then put first char
                 * in uppercase
                 */
                $survivor->setFirstname(
                        ucfirst(strtolower($survivor->getFirstname()))
                        );
                $survivor->setLastname(
                        ucfirst(strtolower($survivor->getLastname()))
                        );
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
        //If survivor hasn't camp it is possible to assign him onto a camp
        if ($survivor->getCamp() == null) {
            $session->set('csrf_token_assign_camp', sha1(time() * rand()));
        } else {
            //If survivor has a camp he can leave his camp
            $session->set('csrf_token_leave_camp', sha1(time() * rand()));
        }       
        return $this->render('XaliSurvivorBundle:Profile:profile.html.twig',
                array(
                    'survivor' => $survivor
                ));
    }
    
    /**
     * A survivor leave his camp
     * 
     * @param integer $survivor_id the survivor's id
     * @param integer $camp_id the camp's id to leave
     * @throws createNotFoundException
     * @throws createAccessDeniedException
     */
    public function leave_campAction($survivor_id, $camp_id)
    {
        /**
         * @var \Xali\Bundle\UserBundle\RightsManager\XaliRightsManager
         * Service which manager rights
         */
        $rightsManager = $this->get('xali_user.rightsmanager');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $survivorRepo = $em->getRepository('XaliSurvivorBundle:Survivor');
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $camp = $campRepo->find($camp_id);
        $survivor = $survivorRepo->findWithCamp($survivor_id);
        $session = $this->get('session');
        $request = $this->get('request');
        //If camp or survivor doesn't exist
        if (!($camp instanceof Camp) || !($survivor instanceof Survivor)) {
            throw $this->createNotFoundException();
        }
        //If volunteer try to manage a survivor in an other camp and is not root
        if (!$rightsManager->userBelongsToCamp($user, $camp) &&
                 !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            throw $this->createAccessDeniedException();
        }
        //If survivor doesn't belong to this camp
        if (!$rightsManager->survivorBelongsToCamp($survivor, $camp)) {
            throw $this->createNotFoundException();
        }
        $token = $session->get('csrf_token_leave_camp');
        $givenToken = $request->request->get('csrf_token_leave_camp');
        //If tokens don't exist are invalids
        if (!$rightsManager->areValidsTokens($givenToken, $token)) {
            throw $this->createAccessDeniedException();
        }
        $survivor->setCamp(null);
        $em->flush();
        return $this->redirect($this->generateUrl('xali_survivor_profile_profile',
                array(
                    'id' => $survivor->getId()
                )));
    }
    
    /**
     * Assign a survivor to a camp
     * 
     * @param type $survivor_id the survivor's id
     * @param type $camp_id the camp's id
     * @throws createNotFoundException
     * @throws createAccessDeniedException
     */
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
        if (!$rightsManager->userBelongsToCamp($user, $camp) &&
                 !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            throw $this->createAccessDeniedException();
        }
        //If survivor already belong to a camp
        if ($survivor->getCamp() != null) {
            throw $this->createAccessDeniedException();
        }
        $token = $session->get('csrf_token_assign_camp');
        $givenToken = $request->request->get('csrf_token_assign_camp');
        //If tokens are invalids
        if (!$rightsManager->areValidsTokens($givenToken, $token)) {
            throw $this->createAccessDeniedException();
        }
        $survivor->setCamp($camp);
        $em->flush();
        return $this->redirect($this->generateUrl('xali_survivor_profile_profile',
                array(
                    'id' => $survivor->getId()
                )));
    }
    
    /**
     * Search a survivor and display results
     */
    public function searchAction()
    {
        $request = $this->get('request');
        $survivor = new Survivor();
        $form = $this->createForm(new SurvivorSearchType(), $survivor);
        $results = null;
        $em = $this->getDoctrine()->getManager();
        $survivorRepo = $em->getRepository('XaliSurvivorBundle:Survivor');
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $results = $survivorRepo->search($survivor);
            }
        }
        
        return $this->render('XaliSurvivorBundle:Search:search.html.twig',
                array('form' => $form->createView(), 'results' => $results));
    }
    


}
