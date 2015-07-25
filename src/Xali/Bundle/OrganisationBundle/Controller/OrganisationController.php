<?php

namespace Xali\Bundle\OrganisationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\OrganisationBundle\Form\OrganisationType;
use Xali\Bundle\OrganisationBundle\Entity\Organisation;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * Manage organisations
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class OrganisationController extends Controller
{
    /**
     * Add an organisation and assign to it a manager
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function add_organisationAction()
    {
        $organisation = new Organisation();
        $form = $this->createForm(new OrganisationType(), $organisation);
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('XaliUserBundle:User');
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        $error = null;
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $userRepo->findOneByEmail(
                                            $request->request->get('manager'));
                $error = $orgRepo->insertOrganisation($manager, $organisation);
            }
        }
        
        $render = 'XaliOrganisationBundle:Management:add_organisation.html.twig';
        return $this->render($render, array('form' => $form->createView(), 'error' => $error));
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
