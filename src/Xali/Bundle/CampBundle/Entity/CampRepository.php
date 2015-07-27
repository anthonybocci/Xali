<?php

namespace Xali\Bundle\CampBundle\Entity;

/**
 * CampRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CampRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Count people (usually User or Survivor) in a given camp
     * 
     * @param Xali\Bundle\CampBundle\Entity\Camp $camp
     * @param string $class like XaliUserBundle:User for e.g
     * @return integer
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function countPeople($camp, $class)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        return $queryBuilder->select('COUNT(DISTINCT(p))')
                     ->from($class, 'p')
                     ->where('p.camp = :camp')
                     ->setParameter('camp', $camp)
                     ->getQuery()
                     ->getSingleScalarResult()
            ;
    }
    
}
