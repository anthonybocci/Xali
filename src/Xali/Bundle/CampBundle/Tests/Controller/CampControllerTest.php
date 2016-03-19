<?php

namespace Xali\Bundle\CampBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CampControllerTest extends WebTestCase
{
    private $campId = "1501";
    
    private $organizationid = "101";


    public function login()
    {
        $client = static::createClient();
        
        //Login, to access to other pages
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton("Submit")->form();
        $form['_username'] = "root-root0";
        $form['_password'] = "root";
        $crawler = $client->submit($form);
        return $client;
    }
    
    
    /**
     * See all camp
     */
    public function testSee_all()
    {
        $client = $this->login();
        
        
        //Needs an organisation with id 101 to display all its camps
        $crawler = $client->request('GET', "/camp/see_all/".$this->organizationid);
        
        $campSeeAllSuccess = $client->getResponse()->isSuccessful();
        if (!$campSeeAllSuccess) {
            echo "\nA GET request is done at /camp/see_all/".$this->organizationid.", you should have an "
            . "organization with the id ".$this->organizationid." in database\n";
        }
        $this->assertTrue($campSeeAllSuccess);
        //The HTML camp's container 
        $this->assertGreaterThan(0, $crawler->filter('div.text-center > div.row')->count());
    }
    
    
    /**
     * Test profile
     */
    public function testProfile()
    {
        $client = $this->login();
        $crawler = $client->request('GET', "/camp/profile/".$this->campId);
        
        $campProfileSuccess = $client->getResponse()->isSuccessful();
        if (!$campProfileSuccess) {
            echo "\nA GET request is done at /camp/profile/".$this->campId.", you should have a "
            . "camp with id ".$this->campId." in database\n";
        }
        
        $this->assertTrue($campProfileSuccess);
        $this->assertEquals(4, $crawler->filter("a.btn.btn-info")->count());
    }
    
    /**
     * Test add camp
     */
    public function testAdd_camp()
    {
        $client = $this->login();
        $crawler = $client->request("GET", "/camp/".$this->organizationid."/add_camp");
        
        $addCampSuccess = $client->getResponse()->isSuccessful();
        if (!$addCampSuccess) {
            echo "\nA GET request is done at /camp/".$this->organizationid."/add_camp, you should have an "
            . "organization with id ".$this->organizationid." in database\n";
        }
        $this->assertTrue($addCampSuccess);
        $this->assertEquals(1, $crawler->filter("form")->count());
        
        //Add a new camp
        $form = $crawler->selectButton("Submit")->form();
        $form['xali_bundle_campbundle_camp[name]'] = "CAMP TEST";
        $form['xali_bundle_campbundle_camp[city]'] = "Paris";
        $form['xali_bundle_campbundle_camp[country]'] = "France";
        $form['location'] = "Paris, France";
        $crawler = $client->submit($form);
  
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->request("GET", "/camp/see_all/".$this->organizationid);
        $this->assertGreaterThan(0, $crawler->filter('html:contains("CAMP TEST")')->count());
    }
    
    /**
     * Test delete a camp
     */
    public function testDelete_camp()
    {
        $client = $this->login();
        $crawler = $client->request('GET', "/camp/see_all/".$this->organizationid);
        
        $link = $crawler->filter('a:contains("CAMP TEST")')->eq(0)->link();
        
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());
        
        $form = $crawler->selectButton("Confirm deleting")->form();
        $crawler = $client->submit($form);
        
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
    
    /**
     * Test see volonteers
     */
    public function testSee_volunteers()
    {
        $client = $this->login();
        $crawler = $client->request('GET', "/camp/see_all/".$this->organizationid);
        //$link is the link to the first camp in the organization
        $link = $crawler->filter("div.text-center > div.row > div > a")->eq(0)->link();
        $crawler = $client->click($link);
        //$link is the link to view camp's volunteers
        $link = $crawler->filter('div.row > div > a:contains("View volunteers")')->link();
        $crawler = $client->click($link);
        
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("\'s volunteer")')->count());
    }
    
    /**
     * Test see survivors
     */
    public function testSee_survivors()
    {
        $client = $this->login();
        $crawler = $client->request('GET', "/camp/see_all/".$this->organizationid);
        //$link is the link to the first camp in the organization
        $link = $crawler->filter("div.text-center > div.row > div > a")->eq(0)->link();
        $crawler = $client->click($link);
        //$link is the link to view camp's volunteers
        $link = $crawler->filter('div.row > div > a:contains("View survivors")')->link();
        $crawler = $client->click($link);
        
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("\'s survivor")')->count());
    }
}
