<?php
$this->startSetup();

$csvFilePath = dirname(dirname(__DIR__)) . '/data/standard.csv';

/* @var $model Ampersand_Shipping_Model_Resource_Carrier_Standard */
$model = Mage::getResourceModel('ampersand_shipping/carrier_standard');
$csvData = $model->convertCsvToArray($csvFilePath);
$model->saveImportData($csvData);

$this->endSetup();