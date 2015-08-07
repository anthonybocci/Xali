<?php

namespace Xali\Bundle\UserBundle\RightsManager;

use \Doctrine\ORM\EntityManager;
use \Xali\Bundle\UserBundle\Entity\User;
use \Xali\Bundle\OrganisationBundle\Entity\Organisation;
use \Xali\Bundle\CampBundle\Entity\Camp;

/**
 * XaliRightsManager manage rights on Xali. It verify if a survivor belong to
 * a camp before a user update him, verify than a user belong to a organisation
 * before became a manager, etc
 */
class XaliRightsManager
{
    
    /**
     * Check if $user is the $organisation's manager
     * 
     * @param User $user the user to check if he is $organisation's manager
     * @param Organisation $organisation the organisation to check if $user is
     * the manager
     * @return boolean true if $user is $organisation's manager, false else
     */
    public function isOrganisationManager($user, $organisation)
    {
        //If $user in not a User (it may be equals to null for e.g)
        if (!($user instanceof User)) {
            return false;
        } elseif (!($organisation instanceof Organisation)) {
            //if $organisation is not an Organisation (it may be equals to null)
            return false;
        } elseif (empty($organisation->getManager())) {
            //If organisation has no manager
            return false;
        }
        return ($user->getId() == $organisation->getManager()->getId());
    }
    
    /**
     * Check if two tokens are valids
     * 
     * @param string $formToken the token received by form
     * @param string $rightToken the right token created and saved in session
     * @return boolean
     */
    public function areValidsTokens($formToken, $rightToken)
    {
        //If at least one token is empty or null
        if (empty($formToken) || empty($rightToken)) {
            return false;
        } elseif ($formToken == $rightToken) {
            //If token are equals
            return true;
        }
        return false;
    }
    
    /**
     * Check if a user can create/update an organisation.
     * Note: check organisation validity (for update for example check if the
     * given id is different to 0) above in controller
     * 
     * @param Xali\Bundle\UserBundle\Entity\User $user
     * @param Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * @return boolean
     */
    public function canUpdateOrganisation($user, $organisation)
    {
        //If user is invalid
        if (!$user instanceof User || !$organisation instanceof Organisation) {
            return false;
        }elseif (!$organisation instanceof Organisation) {
             /* Else if it's for an organisation adding
              * User has to be SUPER_ADMIN
              */
            return in_array("ROLE_SUPER_ADMIN", $user->getRoles());
        } else {
            //Else, user has to be the organisation's manager or root
             return in_array("ROLE_SUPER_ADMIN", $user->getRoles()) || 
                     $this->isOrganisationManager($user, $organisation);
        }
    }
    
    /**
     * Check if the user has rights to add a survivor in a camp
     * 
     * @param Xali\Bundle\UserBundle\Entity\User $user
     * @param Xali\Bundle\CampBundle\Entity\Camp $camp
     * @return boolean
     */
    public function userBelongsToCamp($user, $camp)
    {
        //If $user is not a valid User or $camp is not a valid Camp
        if (!($user instanceof User) || !($camp instanceof Camp)) {
            return false;
        }
        //If user belong to a camp  and he try to add a survivor in its camp
        return ($user->getCamp() != null && 
                $user->getCamp()->getId() == $camp->getId());
    }
    
    /**
     * Check if the survivor belong to the camp
     * Note: check survivor validity (for update for example check if the
     * given id is different to 0) above in controller
     * 
     * @param \Xali\Bundle\SurvivorBundle\Entity\Survivor $survivor
     * @param \Xali\Bundle\CampBundle\Entity\Camp $camp
     * @return boolean
     */
    public function survivorBelongsToCamp($survivor, $camp)
    {
        //If survivor or camp is invalid
        if (!($survivor instanceof Survivor) || !($camp instanceof Camp)) {
            return false;
        }
        /*
         * If survivor has a camp and survivor belong to $camp
         */
        return $survivor->getCamp() != null &&
                $survivor->getCamp()->getId() == $camp->getId();
    }
    
    
    /**
     * Check if two users are the same
     * 
     * @param Xali\Bundle\UserBundle\Entity\User $loggedUser
     * @param Xali\Bundle\UserBundle\Entity\User $givenUser
     * @return boolean
     */
    public function isSameUser($loggedUser, $givenUser)
    {
        //If at least one of users is invalid
        if (!($loggedUser instanceof User) || !($givenUser instanceof User)) {
            return false;
        }
        return $loggedUser->getId() == $givenUser->getId();
    }
}