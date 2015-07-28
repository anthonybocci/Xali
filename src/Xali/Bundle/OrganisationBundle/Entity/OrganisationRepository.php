<?php

namespace Xali\Bundle\OrganisationBundle\Entity;

use Xali\Bundle\UserBundle\Entity\User;
use \Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use \Doctrine\ORM\Query\ResultSetMapping;

/**
 * OrganisationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrganisationRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Insert an organisation if the manager is valid and not null
     * 
     * @param \Xali\Bundle\UserBundle\Entity\User $manager
     * @param \Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * @return $result null or the error string
     */
    public function insertOrganisation($manager, $organisation)
    {
        if (!empty($manager) && $manager instanceof User) {
            $result = null;
            $sql = "INSERT INTO organisation (manager_id, name, dateofcreation)"
            . "VALUES (?, ?, ?)";
            try {
                $stmt = $this->_em->getConnection()->prepare($sql);
                $stmt->execute(
                        array($manager->getId(),
                            $organisation->getName(),
                            $organisation->getDateOfCreation()->format('Y-m-d'),
                        ));
            } catch (UniqueConstraintViolationException $e) {
                $result = "form.error.violation_key";
            } catch (\Exception $e) {
                $result = "form.error.other_error";
            }
        } else { //Manager's email adress is invalid
            $result = "form.error.invalid_email";
        }
        return $result;
    }
    
    /**
     * Update an organisation
     * 
     * @param \Xali\Bundle\UserBundle\Entity\User $manager the manager
     * @param \Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * the organisation
     * @return string
     */
    public function updateOrganisation($manager, $organisation)
    {
        if (!empty($manager) && $manager instanceof User) {
            $result = null;
            $sql = "UPDATE organisation
                    SET manager_id = ?, name = ?, dateofcreation = ?
                    WHERE organisation.id = ?
                    ";
            try {
                $stmt = $this->_em->getConnection()->prepare($sql);
                $stmt->execute(array($manager->getId(), $organisation->getName(),
                            $organisation->getDateOfCreation()->format('Y-m-d'),
                            $organisation->getId()));
            } catch (UniqueConstraintViolationException $e) {
                $result = "form.error.violation_key";
            } catch (\Exception $e) {
                $result = "form.error.other_error";
            }
        } else { //Manager's email adress is invalid
            $result = "form.error.invalid_email";
        }
        return $result;
    }
    
    /**
     * find an organisation, joined with its manager
     * 
     * @param integer $id organisation's id
     * @return Xali\Bundle\OrganisationBundle\Entity\Organisation
     */
    public function findWithManager($id)
    {
        $queryBuilder = $this->createQueryBuilder('org');
        return $queryBuilder->select('o')
                     ->from('XaliOrganisationBundle:Organisation', 'o')
                     ->innerJoin('o.manager', 'm')
                     ->where('o.id = :id')
                     ->setParameter('id', $id)
                     ->getQuery()
                     ->getOneOrNullResult()
            ;
    }
    
    /**
     * Count volunteers in a given organisation
     * 
     * @param \Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * @return integer
     * @author Anthony Bocci Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function countVolunteers(Organisation $organisation)
    {
        $sql = "SELECT COUNT(DISTINCT(u.id))
                FROM XaliUserBundle:User u
                WHERE u.camp IN (
                            SELECT c
                            FROM XaliCampBundle:Camp c
                            WHERE c.organisation = :organisation
                            )
                ";
        $query = $this->_em->createQuery($sql);
        $query->setParameter('organisation', $organisation);
        return $query->getSingleScalarResult();
    }
    
    /**
     * Count camps for a given organisation
     * 
     * @param \Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * the organisation
     * @return integer
     * @author Anthony Bocci Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function countCamps(Organisation $organisation)
    {
        $sql = "SELECT COUNT(DISTINCT(c.id))
                FROM XaliCampBundle:Camp c
                WHERE c.organisation = :organisation
                ";
        $query = $this->_em->createQuery($sql);
        $query->setParameter('organisation', $organisation);
        return $query->getSingleScalarResult();
    }
    
    /**
     * Count survivors for a given organisation
     * 
     * @param \Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * the organisation
     * @return integer
     * @author Anthony Bocci Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function countSurvivors(Organisation $organisation)
    {
        $sql = "SELECT COUNT(DISTINCT(s.id))
                FROM XaliSurvivorBundle:Survivor s
                WHERE s.camp IN (
                            SELECT c
                            FROM XaliCampBundle:Camp c
                            WHERE c.organisation = :organisation
                            )
                ";
        $query = $this->_em->createQuery($sql);
        $query->setParameter('organisation', $organisation);
        return $query->getSingleScalarResult();
    }
}
