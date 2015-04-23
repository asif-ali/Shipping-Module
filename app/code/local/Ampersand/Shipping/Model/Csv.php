<?php

class Ampersand_Shipping_Model_Csv
{
    const FILE_NAME = 'standard.csv';
    const EXPORT_FOLDER = 'export';

    /**
     * Export shipping rates into a CSV
     * @param Varien_File_Csv $csv
     * @return array
     * @author Asif Ali <aa@amp.co>
     */
    public function exportRatesToCsv(Varien_File_Csv $csv)
    {
        $path = Mage::getBaseDir('var') . DS . static::EXPORT_FOLDER;
        if (!is_dir($path)) {
            mkdir($path);
        }

        $csvData = array();
        $file = $path . DS . static::FILE_NAME;
        $rates = $this->_getShippingRates();

        $headers = array(
            'Website Id',
            'Destination',
            'Service',
            'Cost',
            'Description'
        );

        $csvData[] = $headers;

        $csvRow = array();
        foreach ($rates as $rate) {
            $csvRow['website_id'] = $rate->getWebsiteId();
            $csvRow['destination'] = $rate->getDestination();
            $csvRow['service'] = $rate->getService();
            $csvRow['cost'] = $rate->getCost();
            $csvRow['description'] = $rate->getDescription();

            $csvData[] = $csvRow;
        }

        $csv->saveData($file, $csvData);

        return $this->_getCsvFileContent();
    }

    /**
     * @return Ampersand_Shipping_Model_Resource_Carrier_Standard_Collection
     * @author Asif Ali <aa@amp.co>
     */
    protected function _getShippingRates()
    {
        return Mage::getResourceModel('ampersand_shipping/carrier_standard_collection');
    }

    /**
     * @return array
     * @author Asif Ali <aa@amp.co>
     */
    protected function _getCsvFileContent()
    {
        $path = Mage::getBaseDir('var') . DS . static::EXPORT_FOLDER;
        $file = $path . DS . static::FILE_NAME;

        return array(
            'type' => 'filename',
            'value' => $file,
            'rm' => true // can delete file after use
        );
    }
}