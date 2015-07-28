<?php

namespace Xali\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\UserBundle\Entity\User;
use Xali\Bundle\UserBundle\Form\ProfileUserType;

/**
 * Controller managing the user profile
 *
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class ProfileController extends Controller
{
    /**
     * Show the user
     */
    public function showAction($id_user)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('XaliUserBundle:User');
        $profileOwner = $userRepo->findWithCamp($id_user);
        if ( !($profileOwner instanceof User) || empty($profileOwner)) {
            throw $this->createNotFoundException();
        }
        return $this->render('XaliUserBundle:Profile:show.html.twig', array(
                                                'profileOwner' => $profileOwner,
        ));
    }

    /**
     * Edit the user
     */
    public function editAction(User $givenUser)
    {
        $user = $this->getUser();
        //If user want to update an other profile
        if ($user->getId() != $givenUser->getId()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(new ProfileUserType(), $user);
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
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
