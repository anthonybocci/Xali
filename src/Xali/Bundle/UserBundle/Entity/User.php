<?php

namespace Xali\Bundle\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Xali\Bundle\UserBundle\Entity\UserRepository")
 */
class User implements UserInterface
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
     * @Gedmo\Slug(fields={"firstname", "lastname"})
     * @ORM\Column(name="username", type="string", length=201, unique=true) 
     */
    private $username;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="salt", type="string", length=255)
     */
     private $salt;

    /**
     * @var array
     * 
     * @ORM\Column(name="roles", type="array")
     */
     private $roles;
    
     /**
      * @var string
      * 
      * @ORM\Column(name="password", type="string")
      */
     private $password;


     /**
     * @var string
     * 
     * @ORM\Column(name="firstname", type="string", length=100) 
     * @Assert\NotBlank()
     */
    private $firstname;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="lastname", type="string", length=100)
     * @Assert\NotBlank() 
     */
    private $lastname;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="email", type="string", length=100, unique=true) 
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $email;
    
    /**
     * @var string
     * 
     * @Assert\NotBlank()
     */
    private $plainPassword;
    

    public function __construct()
    {
        $this->roles = array();
        $this->roles[] = "ROLE_USER";
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
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
     * @param string $firstname
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
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
     * @param string $lastname
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }
    
  

    public function eraseCredentials()
    {
        
    }

    /**
     * Get password
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * Set password
     * 
     * @param string $password
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get roles
     * 
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
    
    /**
     * Set roles
     * 
     * @param array $roles
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }
    
    /**
     * Add roles
     * 
     * @param array $role
     */
    public function addRoles($roles)
    {
        foreach ($roles as $role) {
            $this->roles[] = $role;
        }
    }

    /**
     * Get salt
     * 
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * Set salt
     * 
     * @param string $salt
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Get username
     * 
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Set username
     * 
     * @param string $username
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    
    /**
     * Get email
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Set email
     * 
     * @param string $p_email
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setEmail($p_email)
    {
        $this->email= $p_email;
        return $this;
    }
    
    /**
     * Get plainPassword
     * 
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    
    /**
     * Set plainPassword
     * 
     * @param string $p_plainPassword
     * @return \Xali\Bundle\UserBundle\Entity\User
     */
    public function setPlainPassword($p_plainPassword)
    {
        $this->plainPassword = $p_plainPassword;
        return $this;
    }

}

