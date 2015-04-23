<?php

class Ampersand_Shipping_Model_Source_Time extends Varien_Object
{
    /**
     * Time source model
     * Full hours, e.g 12:00
     * @return array
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    static public function toOptionArray()
    {
        $options = array();

        for ($h = 0; $h < 24; $h++) {
            $hour = $h;
            if ($hour < 10) {
                $hour = '0' . $hour;
            }
            $options[$hour] = $hour . ':00';
        }

        return $options;
    }

}