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
     * @param Xali\Bundle\CampBundle\Entity\Camp $camp
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function profileAction(Camp $camp)
    {
        return $this->render('XaliCampBundle:Profile:profile.html.twig', array(
                                                                'camp' => $camp,
                            ));
    }
    
    /**
     * Assign a volunteer to a camp
     * 
     * @param Xali\Bundle\CampBundle\Entity\Camp $camp
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function assign_volunteerAction(Camp $camp)
    {
        $insert = null;
        $request = $this->get('request');
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('XaliUserBundle:User');
        //If form is submitted and tokens and email exist
        if ($request->getMethod() == "POST" &&
                            !empty($session->get('csrf_token')) &&
                            !empty($request->request->get('csrf_token')) &&
                            !empty($request->request->get('volunteer_email'))
        ) {
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
        return $this->render($render, array('camp' => $camp, 'insertSuccess' => $insert));
    }
}
