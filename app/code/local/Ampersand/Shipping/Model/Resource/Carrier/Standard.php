<?php

class Ampersand_Shipping_Model_Resource_Carrier_Standard
    extends Mage_Core_Model_Resource_Db_Abstract
{
    const ROW_LENGTH = 5;

    protected $_fileIo;
    protected $_importErrors;
    protected $_importedRows;

    /**
     * @author Asif Ali <aa@amp.co>
     */
    protected function _construct()
    {
        $this->_init('ampersand_shipping/standardShippingRates', 'id');

        $this->_fileIo = new Varien_Io_File();
        $this->_importErrors = array();
        $this->_importedRows = 0;
    }

    /**
     * Upload and import shipping methods csv file
     *
     * @param Varien_Object $object
     * @return Ampersand_Shipping_Model_Resource_Carrier_Standard
     * @author Asif Ali <aa@amp.co>
     */
    public function uploadAndImport(Varien_Object $object)
    {
        if (empty($_FILES['groups']['tmp_name']['standard']['fields']['import']['value'])) {
            return $this;
        }

        $csvFile = $_FILES['groups']['tmp_name']['standard']['fields']['import']['value'];
        $csvName = $_FILES["groups"]["name"]["standard"]["fields"]["import"]["value"];

        //Convert csv to data array
        $importData = $this->convertCsvToArray($csvFile);

        //Save the data
        $this->saveImportData($importData);

        //Check for errors
        if ($this->_importErrors) {
            $error = Mage::helper('shipping')->__(
                'File has not been imported. See the following list of errors: %s',
                implode(" \n", $this->_importErrors)
            );
            Mage::throwException($error);
        }

        return $this;
    }

    /**
     * @param $csvFilePath
     * @return array
     */
    public function convertCsvToArray($csvFilePath)
    {
        $info = pathinfo($csvFilePath);
        $this->_fileIo->open(array('path' => $info['dirname']));
        $this->_fileIo->streamOpen($info['basename'], 'r');

        //Check and skip headers
        $headers = $this->_fileIo->streamReadCsv();
        $this->_checkHeaders($headers);

        $rowNumber = 0;
        while (false !== ($csvLine = $this->_fileIo->streamReadCsv())) {
            $rowNumber++;
            if (empty($csvLine)) {
                continue;
            }

            $row = $this->_getImportRow($csvLine, $rowNumber);
            if ($row !== false) {
                $data[] = $row;
            }
        }

        $this->_fileIo->streamClose();

        return $data;
    }

    /**
     * @param boolean|array $headers
     * @return boolean
     * @author Asif Ali <aa@amp.co>
     */
    protected function _checkHeaders($headers)
    {
        if ($headers === false || count($headers) < self::ROW_LENGTH) {
            $this->_fileIo->streamClose();
            Mage::throwException(
                Mage::helper('ampersand_shipping')->__('Invalid File Format')
            );
        }
    }

    /**
     * Validate row for import and return rates array or false
     * @param $row
     * @param int $rowNumber
     * @return array|bool
     * @author Asif Ali <aa@amp.co>
     */
    protected function _getImportRow($row, $rowNumber = 0)
    {
        if (count($row) < self::ROW_LENGTH) {
            $this->_importErrors[] = Mage::helper('shipping')->__(
                'Invalid Rates format in the Row #%s',
                $rowNumber
            );

            return false;
        }

        // strip whitespace from the beginning and end of each row
        foreach ($row as $k => $v) {
            $row[$k] = trim($v);
        }

        return array(
            'website_id' => $row[0],
            'destination' => $row[1],
            'service' => $row[2],
            'cost' => $row[3],
            'description' => $row[4]
        );
    }

    /**
     * @param array $data
     * @return $this
     */
    public function saveImportData(array $data)
    {
        if (!empty($data)) {
            try {
                $adapter = $this->_getWriteAdapter();
                $adapter->beginTransaction();
                $columns = array('website_id', 'destination', 'service', 'cost', 'description');
                foreach ($data as $d) {
                    $condition = array(
                        'website_id = ?' => $d['website_id'],
                        'destination = ?' => $d['destination'],
                        'service = ?' => $d['service'],
                        'description = ?' => $d['description']
                    );
                    $this->_deleteRow($condition);
                    $this->_insertRow($columns, array($d));
                }
                $this->_importedRows = count($data);
            } catch (Mage_Core_Exception $e) {
                $adapter->rollback();
                Mage::throwException($e->getMessage());
            } catch (Exception $e) {
                $adapter->rollback();
                Mage::logException($e);
                Mage::throwException(
                    Mage::helper('shipping')->__('An error occurred while import shipping rates.')
                );
            }

            $adapter->commit();
        }

        return $this;
    }

    /**
     * @param array $columns
     * @param array $data
     * @author Asif Ali <aa@amp.co>
     */
    protected function _insertRow($columns, $data)
    {
        $this->_getWriteAdapter()->insertArray($this->getMainTable(), $columns, $data);
    }

    /**
     * @param array $condition
     * @author Asif Ali <aa@amp.co>
     */
    protected function _deleteRow($condition)
    {
        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
    }

    /**
     * @author asif.ali@ampersandcommerce.com
     */
    public function deleteAll()
    {
        $collection = Mage::getResourceModel('ampersand_shipping/carrier_standard_collection');
        foreach ($collection as $item) {
            $condition = array(
                'id' => $item->getId()
            );
            $this->_deleteRow($condition);
        }
    }
}