<?php

namespace Xali\Bundle\OrganisationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xali\Bundle\UserBundle\Entity\User;

/**
 * Organisation
 *
 * @ORM\Table(name="organisation")
 * @ORM\Entity(repositoryClass="Xali\Bundle\OrganisationBundle\Entity\OrganisationRepository")
 */
class Organisation
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
     * @var \DateTime
     * 
     * @ORM\Column(name="dateofcreation", type="date")
     */
    private $dateOfCreation;

    /**
     * @var \Xali\Bundle\UserBundle\Entity\User
     *
     * @ORM\OneToOne(targetEntity="Xali\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manager;

    public function __construct()
    {
        $this->manager = new User();
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
     * @return Organisation
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
     * Set manager
     *
     * @param Xali\Bundle\UserBundle\Entity\User $manager
     *
     * @return Organisation
     */
    public function setManager(User $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return string
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set dateOfCreation
     *
     * @param \DateTime $dateOfCreation
     *
     * @return Organisation
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
