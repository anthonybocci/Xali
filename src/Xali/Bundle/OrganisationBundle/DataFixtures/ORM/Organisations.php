<?php

namespace Xali\OrganisationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use \Xali\Bundle\OrganisationBundle\Entity\Organisation;

/**
 * Load organisations in database
 */
class LoadOrganisations extends AbstractFixture implements OrderedFixtureInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository('XaliUserBundle:User')->findAll();
        $organisations = array();
        $dateOfCreation = new \DateTime();
        //Create organisations
        for($i = 0; $i < 100; $i++) {
            $organisation = new Organisation();
            //Set organisation
            $organisation->setDateOfCreation($dateOfCreation);
            $organisation->setName($i);
            $organisation->setManager($users[$i]);
            $users[$i]->addRoles(array('ROLE_ORGANISATION'));
            $organisations[] = $organisation;
            $manager->persist($organisation);
            $manager->persist($users[$i]);
        }
        $manager->flush();
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
    
}