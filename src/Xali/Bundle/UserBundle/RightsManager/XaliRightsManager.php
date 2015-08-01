<?php

namespace Xali\Bundle\UserBundle\RightsManager;

use \Doctrine\ORM\EntityManager;

/**
 * XaliRightsManager manage rights on Xali. It verify if a survivor belong to
 * a camp before a user update him, verify than a user belong to a organisation
 * before became a manager, etc
 */
class XaliRightsManager
{
    /**
     * Entity Manager to manage entity
     * 
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $em;
    
    /**
     * Constructor
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}