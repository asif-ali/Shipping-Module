<?php

class Ampersand_Shipping_Model_Carrier_Standard
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'standard';

    /**
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     * @author Asif Ali <aa@amp.co>
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        /* @var $rateResult Mage_Shipping_Model_Rate_Result */
        $rateResult = Mage::getModel('shipping/rate_result');

        $destinationId = $request->getDestCountryId();
        $websiteId = $request->getWebsiteId();

        $rates = $this->_getRates($websiteId, $destinationId);

        if (!$rates->count()) {
            $rateResult->setError('Standard shipping method is not available.');
            return $rateResult;
        }

        $orderTotal = $request->getData('base_subtotal_incl_tax');
        foreach ($rates as $rate) {
            if (strpos($rate->getDescription(), 'Saturday Delivery') !== false) {
                if (($this->_getHelper()->getCurrentDay() == $this->_getHelper()->getSaturdayDeliveryStartDay() &&
                        $this->_getHelper()->getCurrentHour() > $this->_getHelper()->getSaturdayDeliveryStartHour()) ||
                    ($this->_getHelper()->getCurrentDay() == $this->_getHelper()->getSaturdayDeliveryEndDay() &&
                        $this->_getHelper()->getCurrentHour() < $this->_getHelper()->getSaturdayDeliveryEndHour())
                ) {
                    $rateResult->append($this->_getStandardShippingMethod($rate));
                }
            } else if ($rate->getCost() == 0) {
                if ($orderTotal >= $this->_getHelper()->getMinimumAmountForFreeDelivery()) {
                    $rateResult->append($this->_getStandardShippingMethod($rate));
                }
            } else {
                $rateResult->append($this->_getStandardShippingMethod($rate));
            }
        }

        return $rateResult;
    }

    /**
     * @param int $websiteId
     * @param string $destinationId
     * @return Ampersand_Shipping_Model_Resource_Carrier_Standard_Collection
     * @author Asif Ali <aa@amp.co>
     */
    protected function _getRates($websiteId, $destinationId)
    {
        return Mage::getResourceModel('ampersand_shipping/carrier_standard_collection')
            ->getRatesByFilters($websiteId, $destinationId);
    }

    /**
     * @param Ampersand_Shipping_Model_Carrier_Standard $rate
     * @return Mage_Shipping_Model_Rate_Result_Method
     * @author Asif Ali <aa@amp.co>
     */
    protected function _getStandardShippingMethod(Ampersand_Shipping_Model_Carrier_Standard $rate)
    {
        /* @var $method Mage_Shipping_Model_Rate_Result_Method */
        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod(str_replace(' ', '', $rate->getDescription()));
        $method->setMethodTitle($rate->getService() . ' ' . $rate->getDescription());
        $method->setPrice($rate->getCost());
        $method->setCost($rate->getCost());

        return $method;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     * @author Asif Ali <aa@amp.co>
     */
    public function getAllowedMethods()
    {
        return array(
            'standard' => 'Standard',
        );
    }

    /**
     * @return boolean
     * @author Asif Ali <aa@amp.co>
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * @return Ampersand_Shipping_Helper_Data
     * @author Asif Ali <aa@amp.co>
     */
    protected function _getHelper()
    {
        return Mage::helper('ampersand_shipping');
    }
}
