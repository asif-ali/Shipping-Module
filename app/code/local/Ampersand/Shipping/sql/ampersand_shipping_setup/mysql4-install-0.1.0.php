<?php
/**
 * Creating ampersand_shipping_standard_rates table
 *
 * @author Asif Ali <asif.ali@ampersandcommerce.com>
 */
$this->startSetup();

$table = $this->getConnection()
    ->newTable($this->getTable('ampersand_shipping/standardShippingRates'));

$table->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
    array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Shipping rate id')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Website Id')
    ->addColumn('destination', Varien_Db_Ddl_Table::TYPE_TEXT, 3,
        array(
            'nullable' => false,
        ), 'Shipping destination')
    ->addColumn('service', Varien_Db_Ddl_Table::TYPE_TEXT, 5,
        array(
            'nullable' => false,
        ), 'Shipping service')
    ->addColumn('cost', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable' => false,
        ), 'Shipping cost')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 30,
        array(
            'nullable' => false,
        ), 'Shipping description')
    ->addIndex($this->getIdxName('ampersand_shipping/standardShippingRates',
            array('website_id', 'destination', 'service'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX),
        array('website_id', 'destination', 'service'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX))
    ->setComment('Standard shipping rates based on destination.');

$this->getConnection()->createTable($table);

$this->endSetup();