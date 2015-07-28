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
     */
    public function add_organisationAction($id_organisation)
    {
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('XaliUserBundle:User');
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        $givenOrg = $orgRepo->find($id_organisation);
        $organisation = (empty($givenOrg)) ? new Organisation() : $givenOrg;
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
     * @param Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * the organisation user want to display
     * @author Anthony Bocci Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function profileAction(Organisation $organisation)
    {
        $render = 'XaliOrganisationBundle:Profile:profile.html.twig';
        $em = $this->getDoctrine()->getManager();
        $orgRepo = $em->getRepository('XaliOrganisationBundle:Organisation');
        $volunteersNb = $orgRepo->countVolunteers($organisation);
        $campsNb = $orgRepo->countCamps($organisation);
        $survivorsNb = $orgRepo->countSurvivors($organisation);
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
        return $this->render('XaliOrganisationBundle:Search:see_all.html.twig');
    }
}
