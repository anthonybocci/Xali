<?php

namespace Xali\Bundle\CampBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\CampBundle\Entity\Camp;
use Xali\Bundle\CampBundle\Form\CampType;
use Xali\Bundle\OrganisationBundle\Entity\Organisation;

/**
 * Manage camps
 * 
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class CampController extends Controller
{
    /**
     * Add a camp
     * 
     * @param Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function add_campAction(Organisation $organisation)
    {
        //Only the organisation's manager can add a camp
        if ($this->getUser()->getId() != $organisation->getManager()->getId()) {
            throw $this->createAccessDeniedException();
        }
        $camp = new Camp();
        $form = $this->createForm(new CampType, $camp);
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $camp->setOrganisation($organisation);
                $em->persist($camp);
                $em->flush();
            }
        }
        $render = 'XaliCampBundle:Management:add_camp.html.twig';
        return $this->render($render, array(
                                     'form' => $form->createView(),
                                     'organisationId' => $organisation->getId(),
                            ));
    }
    
    /**
     * Display a camp's profile
     * 
     * @param integer $id the camp's id
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function profileAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $campRepo = $entityManager->getRepository('XaliCampBundle:Camp');
        $userClass= "XaliUserBundle:User";
        $survivorClass = "XaliSurvivorBundle:Survivor";
        $camp = $campRepo->findWithOrganisation($id);
        //If camp doesn't exists
        if (!($camp instanceof Camp)) {
            throw $this->createNotFoundException();
        }
        $volunteersNb = $campRepo->countPeople($camp, $userClass);
        $survivorsNb = $campRepo->countPeople($camp, $survivorClass);
        $session = $this->get('session');
        $token = sha1(time() * rand());
        $session->set('csrf_token_del_camp', $token);
        return $this->render('XaliCampBundle:Profile:profile.html.twig', array(
                                                'camp' => $camp,
                                                'volunteersNb' => $volunteersNb,
                                                'survivorsNb' => $survivorsNb,
                            ));
    }
    
    /**
     * Assign a volunteer to a camp
     * 
     * @param integer $id
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function assign_volunteerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $camp = $campRepo->findWithOrganisation($id);
        //If the camp doesn't exist
        if (!($camp instanceof Camp)) {
            throw $this->createNotFoundException();
        } else if ($camp->getOrganisation()->getManager()->getId() != 
                                                    $this->getUser()->getId()) {
            //If the camp doesn't belong to this organisation
            throw $this->createAccessDeniedException();
        }
        $insert = null;
        $request = $this->get('request');
        $session = $this->get('session');
        $userRepository = $em->getRepository('XaliUserBundle:User');
        //If form is submitted and tokens and email exist
        if ($request->getMethod() == "POST" &&
                            !empty($session->get('csrf_token')) &&
                            !empty($request->request->get('csrf_token')) &&
                            !empty($request->request->get('volunteer_email'))) {
            
            $receivedToken = $request->request->get('csrf_token');
            //If received token equals to token in session
            if ($receivedToken == $session->get('csrf_token')) {
                $volunteer = $userRepository->findOneByEmail(
                                    $request->request->get('volunteer_email'));
                //Assign user if email is valid
                $insert = $userRepository->updateCamp($volunteer, $camp);
            }
        }
        //Set csrf token
        $session->set('csrf_token', sha1(time() * rand()));
        $render = 'XaliCampBundle:Management:assign_volunteer.html.twig';
        return $this->render($render, array(
                                'camp' => $camp, 'insertSuccess' => $insert));
    }
    
    /**
     * Show all camps
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function see_allAction()
    {
        $em = $this->getDoctrine()->getManager();
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $camps = $campRepo->findAll();
        return $this->render('XaliCampBundle:Search:see_all.html.twig',
                array('camps' => $camps));
    }
    
    /**
     * Delete a camp
     * 
     * @param Xali\Bundle\CampBundle\Entity\Camp $camp
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     * @throws createAccessDeniedException
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $session = $this->get('session');
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $camp = $campRepo->findWithOrganisation($id);
        $user = $this->getUser();
        //If camp doesn't exist
        if (!($camp instanceof Camp)) {
            throw $this->createNotFoundException();
        }
        //If user is not organisation's manager
        if ($user->getId() != $camp->getOrganisation()->getManager()->getId()) {
            throw $this->createAccessDeniedException();
        }
        $sessionToken = $request->request->get('csrf_token_del_camp');
        $givenToken = $session->get('csrf_token_del_camp');
        $generateUrl = 'xali_camp_search_see_all';
        //If tokens exists and are valids
        if (!empty($sessionToken) && !empty($givenToken) && 
                                                $sessionToken == $givenToken) {
            $em->remove($camp);
            $em->flush();
        } else {
            throw $this->createAccessDeniedException();
        }
        return $this->redirect($this->generateUrl($generateUrl));
    }
}
