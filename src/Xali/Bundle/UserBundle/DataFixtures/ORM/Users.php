<?php

namespace Xali\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Xali\Bundle\UserBundle\Entity\User;

/**
 * Load user in database
 */
class LoadUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $usersNumber = 10000;
        
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
        
        $genders = array('m', 'f');
        $firstnames = array($firstnamesMale, $firstnamesFemale);
        $lastnames = array($lastnamesMale, $lastnamesFemale);

        
        //Create users
        for ($i = 0; $i < $usersNumber; $i++) {
            $user = new User();
            $maleOrFemale = rand(0, count($genders)-1);
            $user->setGender($genders[$maleOrFemale]);
            $firstname = $firstnames[$maleOrFemale]
                                [rand(0, count($firstnames[$maleOrFemale])-1)];
            $user->setFirstname($firstname);
            $lastname = $lastnames[$maleOrFemale]
                                 [rand(0, count($lastnames[$maleOrFemale])-1)];
            $user->setLastname($lastname);
            $user->setEmail(
                    strtolower($user->getFirstname()).
                    '@'.strtolower($user->getLastname().$i).'.com');
            $roles = array('ROLE_USER');
            $user->setRoles($roles);
            
            $user->setSalt(md5(uniqid()));

            $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
            ;
            $user->setPassword(
                    $encoder->encodePassword(strtolower($user->getFirstname()), 
                            $user->getSalt())
                    );
            
            
            $user->setUsername(
                   strtolower($user->getFirstname().'-'.$user->getLastname().$i)
                    );
            $user->setCamp(null);
            $manager->persist($user);
        }
        $manager->flush();
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}