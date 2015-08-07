<?php

namespace Xali\Bundle\SurvivorBundle\Tests\Converter;

use Xali\Bundle\SurvivorBundle\Converter\Converter;

/**
 * Class ConverterTest
 * Test service Converter
 */
class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if 1 kilogramme equals to 2.204... pounds
     */
    public function testfromKgToLb()
    {
        $converter = new Converter();
        $result = $converter->fromKgToLb(1);
        $this->assertEquals(2.20458554, $result);
    }
    
    /**
     * Test if 2.204... pounds equals to 1 kilogramme
     */
    public function testfromLbToKg()
    {
        $converter = new Converter();
        $result = $converter->fromLbToKg(2.20458554);
        $this->assertEquals(1, $result);
    }
    
    /**
     * Test if 2.54 centimeters equals to 1 inch
     */
    public function testfromCmToInch()
    {
        $converter = new Converter();
        $result = $converter->fromCmToInch(2.54);
        $this->assertEquals(1, $result);
    }
    
    /**
     * Test if 1 inch equals to 2.54 centimeters
     */
    public function testfromInchToCm()
    {
        $converter = new Converter();
        $result = $converter->fromInchToCm(1);
        $this->assertEquals(2.54, $result);
    }
}