<?php
/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Verifier_Usps_ResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ecg_AddressVerification_Model_Verifier_Usps_Response
     */
    protected $object;

    public function providerFake()
    {
        return array(array('<fakeXml/>'));
    }

    public function providerError()
    {
        return array(array(
            '<Error>
                <Number>1</Number>
                <Description>Test Description</Description>
                <Source>USPS Source</Source>
            </Error>'
        ));
    }

    public function providerOkError()
    {
        return array(array(
            '<AddressValidateResponse>
                <Address>
                    <Error>
                        <Number>2</Number>
                        <Description>Test Description</Description>
                        <Source>USPS Source</Source>
                    </Error>
                </Address>
            </AddressValidateResponse>'
        ));
    }

    public function providerValidate()
    {
        return array(array(
            '<AddressValidateResponse>
                <Address>
                    <Zip5>00000</Zip5>
                    <State>CA</State>
                </Address>
            </AddressValidateResponse>'
        ));
    }

    protected function setUp()
    {
        $this->object = $this->getMock('Ecg_AddressVerification_Model_Verifier_Usps_Response', array('_getXml', 'getRequest'));
    }

    /**
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::init
     */
    public function testInit()
    {
        $body = 'test body';
        $response = new Zend_Http_Response(200, array(), $body);
        $this->assertNull($this->object->getBody());
        $this->assertInstanceOf('Ecg_AddressVerification_Model_Verifier_Usps_Response', $this->object->init($response));
        $this->assertEquals($body, $this->object->getBody());
    }

    /**
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::getRequest
     */
    public function testGetRequest()
    {
        $this->object = new Ecg_AddressVerification_Model_Verifier_Usps_Response();
        $this->assertInstanceOf('Ecg_AddressVerification_Model_Verifier_Usps_Request', $this->object->getRequest());
    }

    /**
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::getHelper
     */
    public function testGetHelper()
    {
        $this->assertInstanceOf('Ecg_AddressVerification_Helper_Data', $this->object->getHelper());
    }

    /**
     * @expectedException Ecg_AddressVerification_Exception
     * @expectedExceptionMessage Invalid Response.
     * @dataProvider providerFake
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::handle
     */
    public function testHandleException1($xml)
    {
        $this->object->expects($this->once())
            ->method('_getXml')
            ->will($this->returnValue(new SimpleXMLElement($xml)));
        $this->object->handle();
    }

    /**
     * @expectedException Ecg_AddressVerification_Exception
     * @dataProvider providerError
     * @expectedExceptionMessage API error 1. Description: Test Description. Source: USPS Source.
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::handle
     */
    public function testHandleException2($xml)
    {
        $this->object->expects($this->once())
            ->method('_getXml')
            ->will($this->returnValue(new SimpleXMLElement($xml)));
        $this->object->handle();
    }

    /**
     * @expectedException Ecg_AddressVerification_Exception
     * @dataProvider providerOkError
     * @expectedExceptionMessage API address validate response error 2. Description: Test Description. Source: USPS Source.
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::handle
     */
    public function testHandleException3($xml)
    {
        $this->object->expects($this->once())
            ->method('_getXml')
            ->will($this->returnValue(new SimpleXMLElement($xml)));
        $this->object->handle();
    }

    /**
     * @expectedException Mage_Core_Exception
     * @dataProvider providerValidate
     * @expectedExceptionMessage Invalid zip code 11111, should be 00000.
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::handle
     */
    public function testHandleException4($xml)
    {
        $request = new Varien_Object();
        $request->setZip5(11111);
        $request->setState('CA');

        $this->object->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->object->expects($this->once())
            ->method('_getXml')
            ->will($this->returnValue(new SimpleXMLElement($xml)));
        $this->object->handle();
    }

    /**
     * @expectedException Mage_Core_Exception
     * @dataProvider providerValidate
     * @expectedExceptionMessage Invalid state AA, should be CA.
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::handle
     */
    public function testHandleException5($xml)
    {
        $request = new Varien_Object();
        $request->setZip5(00000);
        $request->setState('AA');

        $this->object->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->object->expects($this->once())
            ->method('_getXml')
            ->will($this->returnValue(new SimpleXMLElement($xml)));
        $this->object->handle();
    }

    /**
     * @dataProvider providerValidate
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Response::handle
     */
    public function testHandle($xml)
    {
        $request = new Varien_Object();
        $request->setZip5(00000);
        $request->setState('CA');

        $this->object->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->object->expects($this->once())
            ->method('_getXml')
            ->will($this->returnValue(new SimpleXMLElement($xml)));
        $this->assertTrue($this->object->handle());
    }
}
