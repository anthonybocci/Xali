<?php

namespace Xali\Bundle\OrganisationBundle\Entity;

/**
 * OrganisationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrganisationRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Insert an organisation if the manager is not null
     * 
     * @param \Xali\Bundle\UserBundle\Entity\User $manager
     * @param \Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     * @return $result
     */
    public function insertIfManagerNotNull($manager, $organisation)
    {
        if (!empty($manager)) {
            $result = null;
            $sql = "INSERT INTO organisation (manager_id, name) "
            . "VALUES ("
                        . "'". $manager->getId() . "', "
                        . "'" . $organisation->getName() . "'"
                    . ")";
            try {
                $stmt = $this->_em->getConnection()->prepare($sql);
                $stmt->execute();
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $result = "form.error.violation_key";
            } catch (\Exception $e) {
                $result = "form.error.other_error";
            }
            return $result;
        }
    }
}
