<?php
require_once(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))))
    . DIRECTORY_SEPARATOR . 'Test' . DIRECTORY_SEPARATOR . 'MageTestAbstract.php');

class Ampersand_Shipping_Model_Carrier_StandardTest extends PHPUnit_Framework_TestCase
{
    const TEST_WEBSITE_ID_UK = 1;
    const TEST_COUNTRY_ID_GB = 'GB';

    /**
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testCollectRates()
    {
        $model = $this->getMock(
            'Ampersand_Shipping_Model_Carrier_Standard',
            array(
                '_getRates',
                '_getStandardShippingMethod'
            )
        );

        //Using 'new' because mocking collection requires mocking a long list of methods from core Magento
        $collection = new Ampersand_Shipping_Model_Resource_Carrier_Standard_Collection;
        $model->expects($this->once())
            ->method('_getRates')
            ->will($this->returnValue($collection));

        $rateResultMethod = $this->getMock('Mage_Shipping_Model_Rate_Result_Method');
        $model->expects($this->any())
            ->method('_getStandardShippingMethod')
            ->will($this->returnValue($rateResultMethod));

        $rateRequest = $this->getMock('Mage_Shipping_Model_Rate_Request');

        $rateResult = $model->collectRates($rateRequest);
        $this->assertInstanceOf('Mage_Shipping_Model_Rate_Result', $rateResult);
        $this->assertTrue(is_array($rateResult->getAllRates()));
    }

    /**
     * Test protected _getRates using ReflectionClass
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testGetRates()
    {
        $model = $this->getMock('Ampersand_Shipping_Model_Carrier_Standard');

        $params = array(self::TEST_WEBSITE_ID_UK, self::TEST_COUNTRY_ID_GB);
        $result = Test_MageTestAbstract::invokeMethod($model, '_getRates', $params);

        $this->assertInstanceOf('Ampersand_Shipping_Model_Resource_Carrier_Standard_Collection', $result);
    }

    /**
     * Test protected _getStandardShippingMethod using ReflectionClass
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testGetStandardShippingMethod()
    {
        $model = $this->getMock('Ampersand_Shipping_Model_Carrier_Standard');
        $rate = $this->getMock('Ampersand_Shipping_Model_Carrier_Standard');

        $params = array($rate);
        $result = Test_MageTestAbstract::invokeMethod($model, '_getStandardShippingMethod', $params);

        $this->assertInstanceOf('Mage_Shipping_Model_Rate_Result_Method', $result);
    }

    /**
     * @author Asif Ali <asif.ali@ampersandcommerce.com>
     */
    public function testGetAllowedMethods()
    {
        $model = $this->getMock(
            'Ampersand_Shipping_Model_Carrier_Standard',
            array(
                'getAllowedMethods',
            )
        );

        $allowedMethods = array(
            'standard' => 'Standard',
        );

        $model->expects($this->once())
            ->method('getAllowedMethods')
            ->will($this->returnValue($allowedMethods));

        $this->assertTrue(is_array($model->getAllowedMethods()));
    }

    /**
     * @author Asif Ali <aa@amp.co>
     */
    public function testIsTrackingAvailable()
    {
        $model = $this->getMock(
            'Ampersand_Shipping_Model_Carrier_Standard',
            array(
                'isTrackingAvailable',
            )
        );

        $model->expects($this->once())
            ->method('isTrackingAvailable')
            ->will($this->returnValue(true));

        $this->assertTrue($model->isTrackingAvailable());
    }
}