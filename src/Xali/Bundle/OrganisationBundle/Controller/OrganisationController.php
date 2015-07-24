<?php

namespace Xali\Bundle\OrganisationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\OrganisationBundle\Form\OrganisationType;
use Xali\Bundle\OrganisationBundle\Entity\Organisation;

/**
 * Manage organisations
 */
class OrganisationController extends Controller
{
    /**
     * Add an organisation and assign to it a manager
     */
    public function add_organisationAction()
    {
        $organisation = new Organisation();
        $form = $this->createForm(new OrganisationType(), $organisation);
        $request = $this->get('request');
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository('XaliUserBundle:User');
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $userRepository->findOneByEmail(
                                            $request->request->get('manager'));
                $organisation->setManager($manager);
                $entityManager->persist($organisation);
                $entityManager->flush();
            }
        }
        
        $render = 'XaliOrganisationBundle:Management:add_organisation.html.twig';
        return $this->render($render, array('form' => $form->createView()));
    }
    
    public function update_organisationAction()
    {
        $render = 'XaliOrganisationBundle:Management:update_organisation.html.twig';
        return $this->render($render);
    }
    
    public function profileAction() {
        $render = 'XaliOrganisationBundle:Profile:profile.html.twig';
        return $this->render($render);
    }
}
