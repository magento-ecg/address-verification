<?php
/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Verifier_Usps_RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ecg_AddressVerification_Model_Verifier_Usps_Request
     */
    protected $object;

    public function initProvider()
    {
        return array(array(
            array(
                'street'   => 'Street',
                'state'    => 'CA',
                'postcode' => '00011',
                'company'  => 'Test Firm',
                'city'     => 'Los Angeles',
            ),
            array(
                'config1' => 'value1',
                'config2' => 'value2'
            )
        ));
    }

    public function asXmlProvider()
    {
        return array(array(
            array(
                'user_id'   => 1,
                'firm_name' => 'Test Firm',
                'address2'  => 'Address 2',
                'city'      => 'Los Angeles',
                'state'     => 'CA',
                'zip5'      => '90064',
                'zip4'      => '',
            ),
            '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
            '<AddressValidateRequest USERID="1"><Address><FirmName>Test Firm</FirmName><Address1></Address1>' .
            '<Address2>Address 2</Address2><City>Los Angeles</City><State>CA</State><Zip5>90064</Zip5>' .
            '<Zip4></Zip4></Address></AddressValidateRequest>'
        ));
    }

    protected function setUp()
    {
        $this->object = $this->getMock('Ecg_AddressVerification_Model_Verifier_Usps_Request', array(
            'getResponse', '_getStateById', '_getClient'));
    }

    /**
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Request::getResponse
     */
    public function testGetResponse()
    {
        $object = new Ecg_AddressVerification_Model_Verifier_Usps_Request();
        $this->assertInstanceOf('Ecg_AddressVerification_Model_Verifier_Usps_Response', $object->getResponse());
    }

    /**
     * @dataProvider initProvider
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Request::init
     */
    public function testInit($dataArray, $config)
    {
        $address = new Mage_Customer_Model_Address();

        $this->object->init($address, array());

        $this->assertNull($this->object->getFirmName());
        $this->assertNull($this->object->getCity());
        $this->assertNull($this->object->getState());
        $this->assertEmpty($this->object->getZip5());
        $this->assertEmpty($this->object->getZip4());
        $this->assertEmpty($this->object->getAddress1());
        $this->assertEmpty($this->object->getAddress2());

        $address->addData($dataArray);

        $this->object->expects($this->once())
            ->method('_getStateById')
            ->will($this->returnValue('CA'));

        $this->object->init($address, $config);

        $this->assertEquals($dataArray['street'], $this->object->getAddress2());
        $this->assertEquals($dataArray['city'], $this->object->getCity());
        $this->assertEquals($dataArray['state'], $this->object->getState());
        $this->assertEquals($dataArray['company'], $this->object->getFirmName());
        $this->assertEquals($dataArray['postcode'], $this->object->getZip5());
        $this->assertEquals($config['config1'], $this->object->getConfig1());
        $this->assertEquals($config['config2'], $this->object->getConfig2());
    }

    /**
     * @dataProvider asXmlProvider
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Request::asXml
     */
    public function testAsXml($dataArray, $dataXml)
    {
        $this->object->addData($dataArray);
        $this->assertXmlStringEqualsXmlString($dataXml, $this->object->asXml());
    }

    /**
     * @covers Ecg_AddressVerification_Model_Verifier_Usps_Request::send
     */
    public function testSend()
    {
        $client       = $this->getMock('Zend_Http_Client', array('request'));
        $response     = $this->getMock('Ecg_AddressVerification_Model_Verifier_Usps_Response', array('init'));
        $zendResponse = new Zend_Http_Response(200, array());

        $client->expects($this->once())
            ->method('request')
            ->will($this->returnValue($zendResponse));

        $response->expects($this->once())
            ->method('init')
            ->with($zendResponse)
            ->will($this->returnValue($response));

        $this->object->expects($this->once())
            ->method('_getClient')
            ->will($this->returnValue($client));

        $this->object->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $this->assertInstanceOf('Ecg_AddressVerification_Model_Verifier_Usps_Response', $this->object->send());
    }
}
