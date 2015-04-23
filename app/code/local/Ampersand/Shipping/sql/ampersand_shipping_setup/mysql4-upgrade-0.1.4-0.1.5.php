<?php
$this->startSetup();

//Alter the description column length
$this->getConnection()
    ->modifyColumn(
        $this->getTable('ampersand_shipping/standardShippingRates'),
        'description', 'varchar(100)'
    );

$this->endSetup();