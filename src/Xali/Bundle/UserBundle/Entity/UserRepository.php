<?php

namespace Xali\Bundle\UserBundle\Entity;

use Xali\Bundle\CampBundle\Entity\Camp;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Update the user's camp
     * 
     * @param \Xali\Bundle\UserBundle\Entity\User $volunteer
     * @param \Xali\Bundle\CampBundle\Entity\Camp $camp
     * @param \Xali\Bundle\OrganisationBundle\Entity\Organisation
     * @return integer -1 if parameters type are invalids, 0 if request failed
     * and 1 if it works, and 2 if volunteerd doesn't belong to this 
     * organisation
     */
    public function updateCamp($volunteer, $camp) {
        $return = 0;
        //If parameters are invalids (usually $volunteer)
        if ($volunteer instanceof User && $camp instanceof Camp) {
            //If user belong to an other organisation
            $volunteerOrganisation = ($volunteer->getCamp() == null) ? 
                    null : $volunteer->getCamp()->getOrganisation();
            if ($volunteerOrganisation != null && 
            $camp->getOrganisation()->getId() != $volunteerOrganisation->getId()) {
                return 2;
            }
            $queryBuilder = $this->createQueryBuilder('u');
            $q = $queryBuilder->update('XaliUserBundle:User', 'u')
                         ->set('u.camp', ':camp')
                         ->setParameter('camp', $camp)
                         ->where('u.id = :user_id')
                         ->setParameter('user_id', $volunteer->getId())
                         ->getQuery()
                    ;
            $return = $q->execute();
        } else {
            $return = -1;
        }
        return $return;
    }
    
    /**
     * Find a user, joined with its camp
     * 
     * @param integer $id
     * @return Xali\Bundle\UserBundle\Entity\User
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function findWithCamp($id)
    {
        return $this->createQueryBuilder('u')
                     ->where('u.id = :user_id')
                     ->setParameter('user_id', $id)
                     ->leftJoin('u.camp', 'c')
                     ->addSelect('c')
                     ->getQuery()
                     ->getOneOrNullResult();
    }
    
    /**
     * Find all user who belong to a given camp
     * @param Xali\Bundle\CampBundle\Entity\Camp $camp
     * @return ArrayCollection
     */
    public function findAllInCamp($camp)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.camp = :camp')
                    ->setParameter('camp', $camp)
                    ->getQuery()
                    ->getResult()
                ;
    }
}
