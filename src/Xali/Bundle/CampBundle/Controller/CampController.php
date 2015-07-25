<?php

namespace Xali\Bundle\CampBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\CampBundle\Entity\Camp;
use Xali\Bundle\CampBundle\Form\CampType;

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
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function add_campAction()
    {
        $camp = new Camp();
        $form = $this->createForm(new CampType, $camp);
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                //TMP while idorganisation param is not set in routing
                $tmpOrg = $em->getRepository('XaliOrganisationBundle:Organisation')->find(1);
                $camp->setOrganisation($tmpOrg);
                
                $em->persist($camp);
                $em->flush();
            }
        }
        
        
        $render = 'XaliCampBundle:Management:add_camp.html.twig';
        return $this->render($render, array(
                                        'form' => $form->createView()
                            ));
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
