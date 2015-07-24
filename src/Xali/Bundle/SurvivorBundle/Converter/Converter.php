<?php

namespace Xali\Bundle\SurvivorBundle\Converter;

/**
 * Manage weight and height's conversion
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class Converter
{
    /**
     * Coefficient between kilogrammes and pounds
     * 
     * @var float
     */
    private static $WEIGHTCOEFFICIENT = 2.20458554;
    
    /**
     *Coefficient beetween centimeters and inches
     * 
     * @var float
     */
    private static $HEIGHTCOEFFICIENT = 2.54;
    
    /**
     * Convert a weight from kilogrammes to pounds
     * 
     * @param float $weightInKg
     * @return float
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function fromKgToLb($weightInKg)
    {
        return $weightInKg * self::$WEIGHTCOEFFICIENT;
    }
    
    /**
     * Convert a weight from pounds to kilogrammes
     * 
     * @param float $weightInLb
     * @return float
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function fromLbToKg($weightInLb)
    {
        return $weightInLb / self::$WEIGHTCOEFFICIENT;
    }
    
    /**
     * Convert a height from centimeters to inches
     * 
     * @param float $heightInCm
     * @return float
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function fromCmToInch($heightInCm)
    {
        return $heightInCm / self::$HEIGHTCOEFFICIENT;
    }
    
    /**
     * Convert a height from inches to centimeters
     * 
     * @param float $heightInInch
     * @return float
     * @author Anthony Bocci <boccianthony@yahoo.fr>
     */
    public function fromInchToCm($heightInInch)
    {
        return $heightInInch * self::$HEIGHTCOEFFICIENT;
    }
    
}