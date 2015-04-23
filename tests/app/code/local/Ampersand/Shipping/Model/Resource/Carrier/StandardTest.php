<?php
require_once(dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))))
    . DIRECTORY_SEPARATOR . 'Test' . DIRECTORY_SEPARATOR . 'MageTestAbstract.php');

class Ampersand_Shipping_Model_Resource_Carrier_StandardTest extends PHPUnit_Framework_TestCase
{
    const ROW_LENGTH = 5;
    const CSV_NAME = 'standard.csv';

    protected $importRow = array(
        1,
        'GB',
        'DPD',
        '4.50',
        'Nex Day Guarenteed'
    );

    /**
     * @author Asif Ali <aa@amp.co>
     */
    public function testConstants()
    {
        $this->assertSame(Ampersand_Shipping_Model_Resource_Carrier_Standard::ROW_LENGTH, static::ROW_LENGTH);
    }

    /**
     * @author Asif Ali <aa@amp.co>
     */
    public function testUploadAndImportFileNotSet()
    {
        $varienObject = $this->getMock('Varien_Object');

        $model = $this->getMock(
            'Ampersand_Shipping_Model_Resource_Carrier_Standard',
            array(
                'convertCsvToArray',
                'saveImportData'
            )
        );

        $model->expects($this->any())
            ->method('convertCsvToArray')
            ->will($this->returnValue(array($this->importRow)));

        $model->expects($this->any())
            ->method('saveImportData')
            ->will($this->returnValue($model));

        $result = $model->uploadAndImport($varienObject);
        $this->assertInstanceOf(get_class($model), $result);
    }

    /**
     * @author Asif Ali <aa@amp.co>
     */
    public function testUploadAndImportThrowsException()
    {
        $varienObject = $this->getMock('Varien_Object');

        //Set these values for testing
        $_FILES['groups']['tmp_name']['standard']['fields']['import']['value'] = static::CSV_NAME;
        $_FILES["groups"]["name"]["standard"]["fields"]["import"]["value"] = static::CSV_NAME;

        $model = $this->getMock(
            'Ampersand_Shipping_Model_Resource_Carrier_Standard',
            array(
                'convertCsvToArray',
                'saveImportData'
            )
        );

        $model->expects($this->any())
            ->method('convertCsvToArray')
            ->will($this->returnValue(array($this->importRow)));

        $model->expects($this->any())
            ->method('saveImportData')
            ->will($this->returnValue($model));

        //Use reflection class to set some errors
        $reflection = new ReflectionClass($model);
        $_importErrors = $reflection->getProperty('_importErrors');
        $_importErrors->setAccessible(true);

        $_importErrors->setValue($model, array('An error occurred.'));

        $this->setExpectedException('Mage_Core_Exception');
        $result = $model->uploadAndImport($varienObject);
    }

    /**
     * @author Asif Ali <aa@amp.co>
     */
    public function testUploadAndImport()
    {
        $varienObject = $this->getMock('Varien_Object');

        //Set these values for testing
        $_FILES['groups']['tmp_name']['standard']['fields']['import']['value'] = 'standard.csv';
        $_FILES["groups"]["name"]["standard"]["fields"]["import"]["value"] = 'standard.csv';

        $model = $this->getMock(
            'Ampersand_Shipping_Model_Resource_Carrier_Standard',
            array(
                'convertCsvToArray',
                'saveImportData'
            )
        );

        $model->expects($this->any())
            ->method('convertCsvToArray')
            ->will($this->returnValue(array($this->importRow)));

        $model->expects($this->any())
            ->method('saveImportData')
            ->will($this->returnValue($model));

        $result = $model->uploadAndImport($varienObject);
        $this->assertInstanceOf(get_class($model), $result);
    }

    /**
     * @author Asif Ali <aa@amp.co>
     */
    public function testConvertCsvToArray()
    {
        $csfFilePath = dirname(dirname(dirname(__DIR__))) . DS . 'data' . DS . static::CSV_NAME;

        $model = $this->getMock(
            'Ampersand_Shipping_Model_Resource_Carrier_Standard',
            array(
                '_getImportRow'
            )
        );

        $model->expects($this->any())
            ->method('_getImportRow')
            ->will($this->returnValue($this->importRow));

        $result = $model->convertCsvToArray($csfFilePath);
        $this->assertTrue(is_array($result));
    }

    /**
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testGetImportRow()
    {
        $model = $this->getMock('Ampersand_Shipping_Model_Resource_Carrier_Standard');

        $params = array($this->importRow);
        $result = Test_MageTestAbstract::invokeMethod($model, '_getImportRow', $params);

        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('website_id', $result);
    }

    /**
     * Test _getImportRow where field count in row is less than row count
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testGetImportRowCountLessThanRowLength()
    {
        $model = $this->getMock('Ampersand_Shipping_Model_Resource_Carrier_Standard');
        $importRow = array('Only one field');
        $params = array($importRow);
        $result = Test_MageTestAbstract::invokeMethod($model, '_getImportRow', $params);

        $this->assertFalse($result);
    }

    /**
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testSaveImportData()
    {
        $model = $this->getMock(
            'Ampersand_Shipping_Model_Resource_Carrier_Standard',
            array(
                '_deleteRow',
                '_insertRow'
            )
        );
        $data = array(
            array(
                'website_id' => $this->importRow[0],
                'destination' => $this->importRow[1],
                'service' => $this->importRow[2],
                'cost' => $this->importRow[3],
                'description' => $this->importRow[4]
            )
        );
        $result = $model->saveImportData($data);

        $this->assertInstanceOf(get_class($model), $result);
    }

    /**
     * Test _checkHeaders throws an exception
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testCheckHeadersThrowException()
    {
        $model = $this->getMock('Ampersand_Shipping_Model_Resource_Carrier_Standard');
        $importRow = array();
        $params = array($importRow);

        $this->setExpectedException('Mage_Core_Exception');
        $result = Test_MageTestAbstract::invokeMethod($model, '_checkHeaders', $params);
    }
}