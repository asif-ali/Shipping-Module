<?php
require_once(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))
    . DIRECTORY_SEPARATOR . 'Test' . DIRECTORY_SEPARATOR . 'MageTestAbstract.php');

class Ampersand_Shipping_Model_CsvTest extends PHPUnit_Framework_TestCase
{
    const FILE_NAME = 'standard.csv';
    const EXPORT_FOLDER = 'export';

    /**
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testConstants()
    {
        $this->assertSame(self::EXPORT_FOLDER, Ampersand_Shipping_Model_Csv::EXPORT_FOLDER);
        $this->assertSame(self::FILE_NAME, Ampersand_Shipping_Model_Csv::FILE_NAME);
    }

    /**
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testExportRatesToCsv()
    {
        $csv = $this->getMock('Varien_File_Csv');

        $model = $this->getMock(
            'Ampersand_Shipping_Model_Csv',
            array(
                '_getShippingRates',
                '_getCsvFileContent'
            )
        );

        $collection = new Ampersand_Shipping_Model_Resource_Carrier_Standard_Collection;
        $model->expects($this->once())
            ->method('_getShippingRates')
            ->will($this->returnValue($collection));
        $model->expects($this->once())
            ->method('_getCsvFileContent')
            ->will($this->returnValue(array()));

        $this->assertTrue(is_array($model->exportRatesToCsv($csv)));
    }

    /**
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testGetShippingRates()
    {
        $model = $this->getMock('Ampersand_Shipping_Model_Csv');

        $params = array();
        $result = Test_MageTestAbstract::invokeMethod($model, '_getShippingRates', $params);

        $this->assertInstanceOf('Ampersand_Shipping_Model_Resource_Carrier_Standard_Collection', $result);
    }

    /**
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testGetCsvFileContent()
    {
        $model = $this->getMock('Ampersand_Shipping_Model_Csv');

        $params = array();
        $result = Test_MageTestAbstract::invokeMethod($model, '_getCsvFileContent', $params);

        $this->assertTrue(is_array($result));
    }
}