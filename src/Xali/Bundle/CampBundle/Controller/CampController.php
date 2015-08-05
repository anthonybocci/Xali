<?php

namespace Xali\Bundle\CampBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Xali\Bundle\CampBundle\Entity\Camp;
use Xali\Bundle\CampBundle\Form\CampType;
use Xali\Bundle\OrganisationBundle\Entity\Organisation;
use Xali\Bundle\UserBundle\RightManager\XaliRightsManager;

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
     * @throws createAccessDeniedException
     * @Secure(roles="ROLE_ORGANISATION")
     */
    public function add_campAction(Organisation $organisation)
    {
        $user = $this->getUser();
        /**
         * @var \Xali\Bundle\UserBundle\RightsManager\XaliRightsManager
         * Service which manager rights
         */
        $rightsManager = $this->get('xali_user.rightsmanager');
        $camp = new Camp();
        $form = $this->createForm(new CampType, $camp);
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        //Only the organisation's manager can add a camp
        if (!$rightsManager->isOrganisationManager($user, $organisation)) {
            throw $this->createAccessDeniedException();
        }
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
     * @throws createNotFoundException
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
     * @throws createAccessDeniedException
     * @throws createNotFoundException
     * @Secure(roles="ROLE_ORGANISATION")
     */
    public function assign_volunteerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $camp = $campRepo->findWithOrganisation($id);
        /**
         * @var \Xali\Bundle\UserBundle\RightsManager\XaliRightsManager
         * Service which manager rights
         */
        $rightsManager = $this->get('xali_user.rightsmanager');
        $user = $this->getUser();
        $organisation = $camp->getOrganisation();
        $insert = null;
        $request = $this->get('request');
        $session = $this->get('session');
        $userRepository = $em->getRepository('XaliUserBundle:User');
        $givenToken = $request->request->get('csrf_token');
        $rightToken = $session->get('csrf_token');
        
        //If the camp doesn't exist
        if (!($camp instanceof Camp)) {
            throw $this->createNotFoundException();
        } else if (
                !$rightsManager->canUpdateOrganisation($user, $organisation)) {
            //If the user is not manager of organisation's camp
            throw $this->createAccessDeniedException();
        }
        
        //If form is submitted and tokens are valids and email exist
        if ($request->getMethod() == "POST" &&
                 $rightsManager->areValidsTokens($givenToken, $rightToken) &&
                            !empty($request->request->get('volunteer_email'))) {
            
                $volunteer = $userRepository->findOneByEmail(
                                    $request->request->get('volunteer_email'));
                //Assign user if email is valid
                $insert = $userRepository->updateCamp($volunteer, $camp);
        }
        //Set csrf token
        $session->set('csrf_token', sha1(time() * rand()));
        $render = 'XaliCampBundle:Management:assign_volunteer.html.twig';
        return $this->render($render, array(
                                'camp' => $camp, 'insertSuccess' => $insert));
    }
    
    /**
     * Show all camps or camps sorted by organisation
     * 
     * @param integer $organisation_id the organisation's id if user want to
     * sort camps by organisation
     * @throw createNotFoundException
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function see_allAction($organisation_id)
    {
        $em = $this->getDoctrine()->getManager();
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        $organisation = $orgRepo->find($organisation_id);
        if (!($organisation instanceof Organisation)) {
            throw $this->createNotFoundException();
        }
        //If no research parameter has been given
        if ($organisation_id == 0) {
            $camps = $campRepo->findAllWithOrganisation();
        } else {
            $camps = $campRepo->findByOrganisation($organisation);
        }                              
        return $this->render('XaliCampBundle:Search:see_all.html.twig',
                array('camps' => $camps));
    }
    
    /**
     * Delete a camp
     * 
     * @param integer $id
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     * @throws createAccessDeniedException
     * @throws createNotFoundException
     * @Secure(roles="ROLE_ORGANISATION")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $session = $this->get('session');
        $campRepo = $em->getRepository('XaliCampBundle:Camp');
        $camp = $campRepo->findWithOrganisation($id);
        $user = $this->getUser();
        /**
         * @var \Xali\Bundle\UserBundle\RightsManager\XaliRightsManager
         * Service which manager rights
         */
        $rightsManager = $this->get('xali_user.rightsmanager');
        
        //If camp doesn't exist
        if (!($camp instanceof Camp)) {
            throw $this->createNotFoundException();
        }
        $organisation = $camp->getOrganisation();
        //If user is not organisation's manager
        if ($rightsManager->canUpdateOrganisation($user, $organisation)) {
            throw $this->createAccessDeniedException();
        }
        $sessionToken = $request->request->get('csrf_token_del_camp');
        $givenToken = $session->get('csrf_token_del_camp');
        $generateUrl = 'xali_camp_search_see_all';
        //If tokens exists and are valids
        if ($rightsManager->areValidsTokens($givenToken, $sessionToken)) {
            $em->remove($camp);
            $em->flush();
        } else {
            throw $this->createAccessDeniedException();
        }
        return $this->redirect($this->generateUrl($generateUrl));
    }
}
