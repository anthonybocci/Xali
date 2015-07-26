<?php

namespace Xali\Bundle\CampBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Xali\Bundle\OrganisationBundle\Entity\Organisation;

/**
 * Camp
 *
 * @ORM\Table(name="camp")
 * @ORM\Entity(repositoryClass="Xali\Bundle\CampBundle\Entity\CampRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Camp
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=100)
     */
    private $country;
    
    /**
     * @var Xali\Bundle\OrganisationBundle\Entity\Organisation
     * 
     * @ORM\ManyToOne(targetEntity="Xali\Bundle\OrganisationBundle\Entity\Organisation") 
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisation;
    
    /**
     * @var \DateTime 
     * 
     * @ORM\Column(name="dateofcreation", type="date")
     */
    private $dateOfCreation;


    
    public function __construct()
    {
        $this->dateOfCreation = new \DateTime();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Camp
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Camp
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Camp
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set organisation
     *
     * @param \Xali\Bundle\OrganisationBundle\Entity\Organisation $organisation
     *
     * @return Camp
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get organisation
     *
     * @return \Xali\Bundle\OrganisationBundle\Entity\Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Set dateOfCreation
     *
     * @param \DateTime $dateOfCreation
     *
     * @return Camp
     */
    public function setDateOfCreation(\DateTime $dateOfCreation)
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    /**
     * Get dateOfCreation
     *
     * @return \DateTime
     */
    public function getDateOfCreation()
    {
        return $this->dateOfCreation;
    }
    
    
}
