<?php
$this->startSetup();

$this->getConnection()->modifyColumn($this->getTable('ampersand_shipping/standardShippingRates'),
    'service', 'varchar(25)');

$this->endSetup();

