<?php

namespace Xali\Bundle\SurvivorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Survivor
 *
 * @ORM\Table(name="survivor")
 * @ORM\Entity(repositoryClass="Xali\Bundle\SurvivorBundle\Entity\SurvivorRepository")
 */
class Survivor
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
     * @ORM\Column(name="firstname", type="string", length=100)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100)
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="eyesColor", type="string", length=50)
     */
    private $eyesColor;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;
    
    /**
     * Weight unit in form
     * 
     * @var string 
     */
    private $weightUnit;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height;
    
    /**
     * Height unit in form
     * 
     * @var string 
     */
    private $heightUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="hairColor", type="string", length=50)
     */
    private $hairColor;


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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Survivor
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Survivor
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Survivor
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set eyesColor
     *
     * @param string $eyesColor
     *
     * @return Survivor
     */
    public function setEyesColor($eyesColor)
    {
        $this->eyesColor = $eyesColor;

        return $this;
    }

    /**
     * Get eyesColor
     *
     * @return string
     */
    public function getEyesColor()
    {
        return $this->eyesColor;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Survivor
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Survivor
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set hairColor
     *
     * @param string $hairColor
     *
     * @return Survivor
     */
    public function setHairColor($hairColor)
    {
        $this->hairColor = $hairColor;

        return $this;
    }

    /**
     * Get hairColor
     *
     * @return string
     */
    public function getHairColor()
    {
        return $this->hairColor;
    }
    
    /**
     * Get weightUnit
     * 
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->weightUnit;
    }
    
    /**
     * Set weightUnit
     * 
     * @param string $weightUnit
     * @return \Xali\Bundle\SurvivorBundle\Entity\Survivor
     */
    public function setWeightUnit($weightUnit)
    {
        $this->weightUnit = $weightUnit;
        return $this;
    }
    
    /**
     * Get heightUnit
     * 
     * @return string
     */
    public function getHeightUnit()
    {
        return $this->weightUnit;
    }
    
    /**
     * Set heightUnit
     * 
     * @param string $heightUnit
     * @return \Xali\Bundle\SurvivorBundle\Entity\Survivor
     */
    public function setHeightUnit($heightUnit)
    {
        $this->heightUnit = $heightUnit;
        return $this;
    }
    
}
