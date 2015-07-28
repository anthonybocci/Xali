<?php

namespace Xali\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\UserBundle\Entity\User;

/**
 * Controller managing the user
 *
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class ManagementController extends Controller
{
    /**
     * Remove the user's camp in order to an other organisation can take on him
     * 
     * @param Xali\Bundle\UserBundle\Entity\User $givenUser
     * @throws createAccessDeniedException
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function leave_organisationAction(User $givenUser)
    {
        $session = $this->get('session');
        $request = $this->get('request');
        $token = $session->get('csrf_token_leave_organisation');
        $givenToken = $request->request->get('csrf_token_leave_organisation');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        //If user want to mange an other user, or tokens are invalid
        if ($user->getId() != $givenUser->getId() || empty($token) || 
                                empty($givenToken) || $token != $givenToken) {
            throw $this->createAccessDeniedException();
        }
        $givenUser->setCamp(null);
        $em->flush();
        return $this->redirect($this->generateUrl('xali_user_profile_show', array(
            'id' => $givenUser->getId()
        )));
    }
}
