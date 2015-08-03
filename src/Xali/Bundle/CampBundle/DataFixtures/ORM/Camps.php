<?php

namespace Xali\CampBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Xali\Bundle\CampBundle\Entity\Camp;

/**
 * Load camps in database
 */
class LoadCamps extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $usersNumber = 10000;
        $countries = array(
            'south africa', 'algeria', 'angola', 'bénin'
        );
        $citiesSouthAfrica = array(
            'tshwane', 'ethekwini', 'ekurhuleni', 'newcastle', 'matjhabeng'
        );
        $citiesAlgeria = array(
            'oran', 'constantine', 'annaba', 'blida', 'batna'
        );
        $citiesAngola = array(
            'luanda', 'huambo', 'lobito', 'benguela', 'lucapa'
        );
        $citiesBenin = array(
            'cotonou', 'abomey-calavi', 'parakou', 'djougou', 'bohicon'
        );
        $cities = array($citiesSouthAfrica, $citiesAlgeria, $citiesAngola, 
                    $citiesBenin);
        
        $dateOfCreation = new \DateTime();
        $organisations = $manager->getRepository(
                'XaliOrganisationBundle:Organisation')->findAll();
        
        $camps = array();
        //Create camps
        for ($i = 0; $i < 1500; $i++) {
            $camp = new Camp();
            $idCountry = rand(0, count($countries)-1);
            //Set city etc using arrays above
            $camp->setCountry($countries[$idCountry]);
            //Set city using the number of city in country $idCountry
            $camp->setCity(
                    $cities[$idCountry][rand(0, count($cities[$idCountry])-1)]);
            $camp->setName($i);
            $camp->setOrganisation($organisations[(int)($i / 15)]);
            $camp->setDateOfCreation($dateOfCreation);
            
            $camps[] = $camp;
            $manager->persist($camp);
        }
        
        //Assign users to camps
        $users = $manager->getRepository('XaliUserBundle:User')->findAll();
        $organisationsNumber = count($manager->getRepository(
                            'XaliOrganisationBundle:Organisation')->findAll());
        
        for ($i = 0; $i < $usersNumber; $i++) {
            //Firsts users has organisations managers
            if ($i < $organisationsNumber) {
                $index = ($i*15 > 0) ? $i*15 -1 : 0;
                $users[$i]->setCamp($camps[$index]);
            } else {
                //Each 15 times, the user has no camp
                if ($i % 15 != 0) {
                    $users[$i]->setCamp($camps[rand(0, count($camps)-1)]);
                }
            }
            $manager->persist($users[$i]);
        }
        
        $manager->flush();
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
    
}