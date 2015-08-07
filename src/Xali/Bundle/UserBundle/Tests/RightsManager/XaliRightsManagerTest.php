<?php

namespace Xali\Bundle\UserBundle\Tests\RightsManager;

use Xali\Bundle\UserBundle\RightsManager\XaliRightsManager;
use Xali\Bundle\UserBundle\Entity\User;
use Xali\Bundle\OrganisationBundle\Entity\Organisation;
use Xali\Bundle\CampBundle\Entity\Camp;
use Xali\Bundle\SurivorBundle\Entity\Survivor;

/**
 * Class XaliRightsManagerTest
 * Test service XaliRightsManager
 */
class XaliRightsManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if isOrganisationManager return a boolean
     */
    public function testisOrganisationManager() {
        $user = new User();
        $organisation = new Organisation();
        $rightsManager = new XaliRightsManager();
        $result = $rightsManager->isOrganisationManager($user, $organisation);
        $this->assertInternalType("boolean", $result);
    }
    
    /**
     * Test if areValidsToken return a boolean
     */
    public function testareValidsTokens()
    {
        $formToken = "aGivenToken";
        $rightToken = "theRightToken";
        $rightManager = new XaliRightsManager();
        $result = $rightManager->areValidsTokens($formToken, $rightToken);
        $this->assertInternalType("boolean", $result);
    }
    
    /**
     * Test if canUpdateOrganisation return a boolean
     */
    public function testcanUpdateOrganisation()
    {
        $user = new User();
        $organisation = new Organisation();
        $rightsManager = new XaliRightsManager();
        $result = $rightsManager->canUpdateOrganisation($user, $organisation);
        $this->assertInternalType("boolean", $result);
    }
    
    /**
     * Test if userBelongsToCamp return a boolean
     */
    public function testuserBelongsToCamp()
    {
        $user = new User();
        $camp = new Camp();
        $rightsManager = new XaliRightsManager();
        $result = $rightsManager->userBelongsToCamp($user, $camp);
        $this->assertInternalType("boolean", $result);
    }
    
    /**
     * Test if survivorBelongsToCamp return a boolean
     */
    public function testsurvivorBelongsToCamp()
    {
        $survivor = new Survivor();
        $camp = new Camp();
        $rightsManager = new XaliRightsManager();
        $result = $rightsManager->survivorBelongsToCamp($survivor, $camp);
        $this->assertInternalType("boolean", $result);
    }
    
    /**
     * Test if isSameUser return a boolean
     */
    public function testisSameUser()
    {
        $user1 = new User();
        $user2 = new User();
        $rightsManager = new XaliRightsManager();
        $result = $rightsManager->isSameUser($user1, $user2);
        $this->assertInternalType("boolean", $result);
    }
    
    
}