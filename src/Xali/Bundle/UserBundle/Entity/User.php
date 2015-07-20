<?php

namespace Xali\Bundle\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Xali\Bundle\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * 
     * @Gedmo\Slug(fields={"firstname", "lastname"})
     */
    protected $username;
    
    /**
     * @var string
     * 
     * @Gedmo\Slug(fields={"firstname", "lastname"})
     */
    protected $usernameCanonical;
    
    
    /**
     * @var string
     * 
     * @ORM\Column(name="firstname", type="string", length=200) 
     */
    private $firstname;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="lastname", type="string", length=200) 
     */
    private $lastname;
    


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
     * Get firstname
     * 
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    
    /**
     * Set firstname
     * 
     * @param string $p_firstname
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setFirstname($p_firstname)
    {
        $this->firstname = $p_firstname;
        return $this;
    }
    
    /**
     * Get lastname
     * 
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    
    /**
     * Set lastname
     * 
     * @param string $p_lastname
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setLastname($p_lastname)
    {
        $this->lastname = $p_lastname;
        return $this;
    }
}

