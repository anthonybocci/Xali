<?php

namespace Xali\Bundle\SurvivorBundle\Entity;

use Xali\Bundle\SurvivorBundle\Converter\Converter;

/**
 * SurvivorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SurvivorRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Find a survivor joined with its camp
     * 
     * @param integer $id the survivor's id
     * @return Xali\Bundle\SurvivorBundle\Entity\Survivor
     */
    public function findWithCamp($id)
    {
        return $this->createQueryBuilder('s')
                    ->leftJoin('s.camp', 'c')
                    ->addSelect('c')
                    ->where('s.id = :survivor_id')
                    ->setParameter('survivor_id', $id)
                    ->getQuery()
                    ->getOneOrNullResult()
                ;
    }
    
    
    public function search(Survivor $survivor)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->leftJoin('s.camp', 'c')
              ->addSelect('c')
              ->where('s.firstname = :firstname')
              ->setParameter('firstname', ucfirst($survivor->getFirstname()))
              ->andWhere('s.lastname = :lastname')
              ->setParameter('lastname', ucfirst($survivor->getLastname()))
                ;
        if (!empty($survivor->getBirthday())) {
            $queryBuilder->andWhere('s.birthday = :birthday')
                         ->setParameter('birthday', $survivor->getBirthday());
        }
        if (!empty($survivor->getEyesColor())) {
            $queryBuilder->andWhere('s.eyesColor = :eyescolor')
                         ->setParameter('eyescolor', $survivor->getEyesColor());
        }
        if (!empty($survivor->getWeight())) {
            //Convert weight if necessary
            if ($survivor->getWeightUnit() == "kg") {
                $converter = new Converter();
                $weight = $converter->fromKgToLb($survivor->getWeight());
                $survivor->setWeight($weight);
            }
            
            $queryBuilder->andWhere(
                    $queryBuilder->expr()->between(
                            ':weight', 's.weight - 5', 's.weight + 5'
                            )
                    )
                    ->setParameter('weight', $survivor->getWeight());
        }
        if (!empty($survivor->getHeight())) {
            //Convert height if necessary
            if ($survivor->getHeightUnit() == "cm") {
                $converter = new Converter();
                $height = $converter->fromCmToInch($survivor->getHeight());
                $survivor->setHeight($height);
            }
            
            $queryBuilder->andWhere(
                    $queryBuilder->expr()->between(
                            ':height', 's.height - 5', 's.height + 5'
                            )
                    )
                         ->setParameter('height', $survivor->getHeight());
        }
        if (!empty($survivor->getHairColor())) {
           $queryBuilder->andWhere('s.hairColor = :haircolor')
                         ->setParameter('haircolor', $survivor->getHairColor());
        }
        return $queryBuilder->getQuery()->getResult();
    }
    
    /**
     * Find all survivors who belong to a given camp
     * @param Xali\Bundle\CampBundle\Entity\Camp $camp
     * @return ArrayCollection
     */
    public function findAllInCamp($camp)
    {
        return $this->createQueryBuilder('s')
                    ->where('s.camp = :camp')
                    ->setParameter('camp', $camp)
                    ->getQuery()
                    ->getResult()
                ;
    }
}
