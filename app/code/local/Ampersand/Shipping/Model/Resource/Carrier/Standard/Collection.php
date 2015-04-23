<?php

class Ampersand_Shipping_Model_Resource_Carrier_Standard_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /*
     * (non-PHPdoc) @see Mage_Core_Model_Resource_Db_Collection_Abstract::_construct()
     * @author Asif Ali <aa@amp.co>
    */
    protected function _construct()
    {
        $this->_init('ampersand_shipping/carrier_standard');
    }

    /**
     * @param int $websiteId
     * @author Asif Ali <aa@amp.co>
     */
    public function filterByWebsiteId($websiteId)
    {
        $this->addFieldToFilter('website_id', $websiteId);
    }

    /**
     * @param string $destination
     * @author Asif Ali <aa@amp.co>
     */
    public function filterByDestination($destination)
    {
        $this->addFieldToFilter('destination', $destination);
    }

    /**
     * @param int $websiteId
     * @param int $destinationId
     * @return Ampersand_Shipping_Model_Resource_Carrier_Standard_Collection
     * @author Asif Ali <aa@amp.co>
     */
    public function getRatesByFilters($websiteId, $destinationId)
    {
        $this->filterByWebsiteId($websiteId);
        $this->filterByDestination($destinationId);

        return $this;
    }
}