<?php

class Ampersand_Shipping_Model_Source_TimeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @author Asif Ali <asif.ali@amp.co>
     */
    public function testToOptionArray()
    {
        $options = Ampersand_Shipping_Model_Source_Time::toOptionArray();

        $this->assertTrue(is_array($options));
        $this->assertCount(24, $options);

        //Test if we have all correct 24 hours
        for ($h = 0; $h < 24; $h++) {
            $hour = $h;
            if ($hour < 10) {
                $hour = '0' . $hour;
            }
            $hour .= ':00';
            $this->assertSame($hour, array_shift($options));
        }
    }
}