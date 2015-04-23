<?php

class Ampersand_Shipping_Adminhtml_StandardController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @author Asif Ali <aa@amp.co>
     */
    public function exportAction()
    {
        $csv = new Varien_File_Csv();
        $content = Mage::getModel('ampersand_shipping/csv')->exportRatesToCsv($csv);

        $this->_prepareDownloadResponse(Ampersand_Shipping_Model_Csv::FILE_NAME, $content);
    }
}