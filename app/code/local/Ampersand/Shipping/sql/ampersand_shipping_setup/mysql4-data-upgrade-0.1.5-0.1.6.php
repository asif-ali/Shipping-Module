<?php
$this->startSetup();

//Update shipping methods
$csvFilePath = dirname(dirname(__DIR__)) . '/data/standard.csv';

$model = Mage::getResourceModel('ampersand_shipping/carrier_standard')->deleteAll();

/* @var $model Ampersand_Shipping_Model_Resource_Carrier_Standard */
$model = Mage::getResourceModel('ampersand_shipping/carrier_standard');
$csvData = $model->convertCsvToArray($csvFilePath);
$model->saveImportData($csvData);

$this->endSetup();