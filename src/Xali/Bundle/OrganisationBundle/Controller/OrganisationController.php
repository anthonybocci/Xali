<?php

namespace Xali\Bundle\OrganisationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\OrganisationBundle\Form\OrganisationType;
use Xali\Bundle\OrganisationBundle\Entity\Organisation;

/**
 * Manage organisations
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class OrganisationController extends Controller
{
    /**
     * Add an organisation and assign to it a manager
     * 
     * @param integer $id_organisation the organisation's id if user want to
     * update an organisation
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     * @throws createNotFoundException
     */
    public function add_organisationAction($id_organisation)
    {
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('XaliUserBundle:User');
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        $organisation = $orgRepo->find($id_organisation);
        //If organisation doesn't exist
        if (!($organisation instanceof Organisation)) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(new OrganisationType(), $organisation);
        $error = null;
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $paramManager = $request->request->get('manager');
                $manager = $userRepo->findOneByEmail($paramManager);
                $result = $this->insertOrUpdate($organisation, $manager);
                //If there is no error, display a message of success
                $error = ($result == null) ? "form.success" : $result;
            }
        }
        $render = 'XaliOrganisationBundle:Management:add_organisation.html.twig';
        return $this->render($render, array('form' => $form->createView(),
                           'error' => $error, 'organisation' => $organisation));
    }
    
    /**
     * Insert or update an organisation according to $organisation's id
     * 
     * @param Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * the organisation
     * @param Xali\Bundle\UserBundle\Entity\User $manager
     * the manager user want to assign
     * @return string error message if there is
     * @author Anthony Bocci Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function insertOrUpdate($organisation, $manager)
    {
        $em = $this->getDoctrine()->getManager();
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        //Insert
        if (empty($organisation->getId())) {
            return $orgRepo->insertOrganisation($manager, $organisation);
        } else { //Update
            return $orgRepo->updateOrganisation($manager, $organisation);
        }
    }
    
    /**
     * Display organisation's profile
     * 
     * @param integer $id
     * the organisation'id user want to display
     * @author Anthony Bocci Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function profileAction($id)
    {
        $render = 'XaliOrganisationBundle:Profile:profile.html.twig';
        $em = $this->getDoctrine()->getManager();
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        $organisation = $orgRepo->findWithManager($id);
        $volunteersNb = $orgRepo->countVolunteers($organisation);
        $campsNb = $orgRepo->countCamps($organisation);
        $survivorsNb = $orgRepo->countSurvivors($organisation);
        $session = $this->get('session');
        $token = sha1(time() * rand());
        $session->set('csrf_token_del_org', $token);
        return $this->render($render, array(
            'organisation' => $organisation,
            'volunteersNb' => $volunteersNb,
            'campsNb' => $campsNb,
            'survivorsNb' => $survivorsNb,
        ));
    }
    
    /**
     * Show all organisations
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function see_allAction()
    {
        $em = $this->getDoctrine()->getManager();
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        $organisations = $orgRepo->findAll();
        return $this->render('XaliOrganisationBundle:Search:see_all.html.twig',
                array('organisations' => $organisations));
    }
    
    /**
     * Delete an organisation
     * 
     * @param integer $id $organisation
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     * @throws createAccessDeniedException
     * @throws createNotFoundException
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        $organisation = $orgRepo->findWithManager($id);
        //If the organisation doesn't exist
        if (!($organisation instanceof Organisation)) {
            throw $this->createNotFoundException();
        } else if ($organisation->getManager()->getId() != 
                                                    $this->getUser()->getId()) {
            //If user is not organisation's manager
            throw $this->createAccessDeniedException();
        }
        $request = $this->get('request');
        $session = $this->get('session');
        $sessionToken = $request->request->get('csrf_token_del_org');
        $givenToken = $session->get('csrf_token_del_org');
        $generateUrl = 'xali_organisation_search_see_all';
        //If tokens exists and are valids
        if (!empty($sessionToken) && !empty($givenToken) && 
                                                $sessionToken == $givenToken) {
            $em->remove($organisation);
            $em->flush();
        } else {
            throw $this->createAccessDeniedException();
        }
        return $this->redirect($this->generateUrl($generateUrl));
    }
}
