<?php

class Ampersand_Shipping_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return int
     * @author Asif Ali <aa@amp.co>
     */
    public function getSaturdayDeliveryStartDay()
    {
        return (int)Mage::getStoreConfig('carriers/standard/constraint_start_day');
    }

    /**
     * @return int
     * @author Asif Ali <aa@amp.co>
     */
    public function getSaturdayDeliveryStartHour()
    {
        return (int)Mage::getStoreConfig('carriers/standard/constraint_start_hour');
    }

    /**
     * @return int
     * @author Asif Ali <aa@amp.co>
     */
    public function getSaturdayDeliveryEndDay()
    {
        return (int)Mage::getStoreConfig('carriers/standard/constraint_end_day');
    }

    /**
     * @return int
     * @author Asif Ali <aa@amp.co>
     */
    public function getSaturdayDeliveryEndHour()
    {
        return (int)Mage::getStoreConfig('carriers/standard/constraint_end_hour');
    }

    /**
     * @return float
     * @author Asif Ali <aa@amp.co>
     */
    public function getMinimumAmountForFreeDelivery()
    {
        return (float)Mage::getStoreConfig('carriers/standard/minimum_amount_for_free_delivery');
    }

    /**
     * @return string
     * @author Asif Ali <aa@amp.co>
     */
    public function getCurrentDay()
    {
        return date("w", time());
    }

    /**
     * @return string
     * @author Asif Ali <aa@amp.co>
     */
    public function getCurrentHour()
    {
        return date("G", time());
    }

    /**
     * @param $trackingNumber
     * @param $store
     * @return mixed
     * @author Asif Ali <aa@amp.co>
     */
    public function getTrackingUrl($trackingNumber, $store = null)
    {
        $trackingUrl = Mage::getStoreConfig('carriers/standard/tracking_url', $store);
        $trackingUrl = str_replace('%s', $trackingNumber, $trackingUrl);

        return $trackingUrl;
    }
}