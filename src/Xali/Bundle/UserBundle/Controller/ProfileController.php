<?php

namespace Xali\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\UserBundle\Entity\User;
use Xali\Bundle\UserBundle\Form\ProfileUserType;
use Xali\Bundle\UserBundle\RightManager\XaliRightsManager;

/**
 * Controller managing the user profile
 *
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class ProfileController extends Controller
{
    /**
     * Show the user
     * 
     * @param integer $id a user id
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function showAction($id)
    {
        $session = $this->get('session');
        $token = sha1(time() * rand());
        $session->set('csrf_token_leave_organisation', $token);
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('XaliUserBundle:User');
        $profileOwner = $userRepo->findWithCamp($id);
        if (!($profileOwner instanceof User)) {
            throw $this->createNotFoundException();
        }
        return $this->render('XaliUserBundle:Profile:show.html.twig', array(
                                                'profileOwner' => $profileOwner,
        ));
    }

    /**
     * Edit the user
     * 
     * @param Xali\Bundle\UserBundle\Entity\User $givenUser the user to edit
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     * @throws createAccessDeniedException
     */
    public function editAction(User $givenUser)
    {
        /**
         * @var \Xali\Bundle\UserBundle\RightsManager\XaliRightsManager
         * Service which manager rights
         */
        $rightsManager = $this->get('xali_user.rightsmanager');
        $user = $this->getUser();
        $session = $this->get('session');
        $request = $this->get('request');
        //If it is not the user's profile
        if (!$rightsManager->isSameUser($user, $givenUser)) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(new ProfileUserType(), $user);
        $em = $this->getDoctrine()->getManager();
        //If the form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->flush();
            }
        }
        return $this->render('XaliUserBundle:Profile:edit.html.twig', array(
                                                   'form' => $form->createView()
        ));
    }
}
