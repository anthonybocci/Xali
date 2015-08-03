<?php

namespace Xali\SurvivorBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Xali\Bundle\SurvivorBundle\Entity\Survivor;

/**
 * Load survivors in database
 */
class LoadSurvivors extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
         $firstnamesMale = array(
            'John', 'Luke', 'Mike', 'Anakin', 'Bruce', 'Clark', 'Peet', 'Jack',
            'Harry', 'Peter', 'Ron', 'Chuck', 'Cordel', 'Aladdin'
        );
        
        $firstnamesFemale = array(
            'Hermione', 'Sarah', 'Jasmine', 'Taslima', 'Alix', 'Padme',
            'Rachel', 'Rose', 'Jane'
            
        );
        
        $lastnamesMale = array(
            'Connor', 'Skywalker', 'Lee', 'Wayne', 'Kent', 'Sparrow', 'Potter',
            'Parker', 'Weasley', 'Norris', 'Walker'
        );
        
        $lastnamesFemale = array(
            'Granger', 'Connor', 'Amidala', 'Kent', 'Parker', 'Potter'
        );
        $firstnames = array($firstnamesMale, $firstnamesFemale);
        $lastnames = array($lastnamesMale, $lastnamesFemale);
        $genders = array('m', 'f');
        
        $survivorsNumber = 50000;
        $camps = $manager->getRepository('XaliCampBundle:Camp')->findAll();
        $eyesColors = array('brown', 'green', 'blue', 'grey', 'black', 'other');
        
        
        $hairColors = array(
                    'blond', 'blue', 'brown', 'pink', 'purple','red', 'other');
        
        
        for ($i = 0; $i < $survivorsNumber; $i++) {
            $maleOrFemale = rand(0, count($genders)-1);
            $survivor = new Survivor();
            $survivor->setGender($genders[$maleOrFemale]);
            //Today minus a number of days between 3 and 10 years
            $daysNumber = rand(1095, 146000);
            $birthday = new \DateTime();
            $birthday->sub(new \DateInterval('P'.$daysNumber.'D'));
            $survivor->setBirthday($birthday);
            $camp = $camps[rand(0, count($camps)-1)];
            //Each 15 times, survivor has no camp
            if ($i % 15 != 0) {
                $survivor->setCamp($camp);
            } else {
                $survivor->setCamp(null);
            }
            $firstname = $firstnames[$maleOrFemale]
                                [rand(0, count($firstnames[$maleOrFemale])-1)];
            $lastname = $lastnames[$maleOrFemale]
                    [rand(0, count($lastnames[$maleOrFemale])-1)];
            $survivor->setFirstname($firstname);
            $survivor->setLastname($lastname);
            $survivor->setHairColor($hairColors[rand(0, count($hairColors)-1)]);
            $survivor->setEyesColor($eyesColors[rand(0, count($eyesColors)-1)]);
            //TODO : improve in order to height and weight be coherent with 
            //birthday
            $survivor->setWeight(rand(10, 190));
            $survivor->setHeight(rand(20, 75));
            $manager->persist($survivor);
        }
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
    
}