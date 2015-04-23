<?php

class Ampersand_Shipping_Model_Adminhtml_System_Config_Backend_Shipping_Standard
    extends Mage_Core_Model_Config_Data
{
    /**
     * @author Asif Ali <aa@amp.co>
     */
    public function _afterSave()
    {
        Mage::getResourceModel('ampersand_shipping/carrier_standard')->uploadAndImport($this);
    }
}