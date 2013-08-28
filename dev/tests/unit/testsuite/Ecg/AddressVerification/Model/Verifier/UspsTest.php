<?php
/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Verifier_UspsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ecg_AddressVerification_Model_Verifier_Usps
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Ecg_AddressVerification_Model_Verifier_Usps();
    }

    /**
     * @covers Ecg_AddressVerification_Model_Verifier_Usps::getRequest
     */
    public function testGetRequest()
    {
        $this->assertInstanceOf('Ecg_AddressVerification_Model_Verifier_Usps_Request', $this->object->getRequest());
    }

    /**
     * @covers Ecg_AddressVerification_Model_Verifier_Usps::getResponse
     */
    public function testGetResponse()
    {
        $this->assertInstanceOf('Ecg_AddressVerification_Model_Verifier_Usps_Response', $this->object->getResponse());
    }

    /**
     * @covers Ecg_AddressVerification_Model_Verifier_Usps::verify
     */
    public function testVerify()
    {
        $this->object = $this->getMock('Ecg_AddressVerification_Model_Verifier_Usps', array(
            'getConfigData', 'getGatewayUrl', 'getRequest', 'getResponse', '_debug'));

        $request = $this->getMock('Ecg_AddressVerification_Model_Verifier_Usps_Request', array('init', 'asXml', 'send'));
        $response = $this->getMock('Ecg_AddressVerification_Model_Verifier_Usps_Response', array('handle'));
        $address = new Mage_Customer_Model_Address();

        $request->expects($this->once())
            ->method('init')
            ->with($address, array(
                'user_id'     => 1,
                'gateway_url' => 'http://example.com',
                'api_code'    => 'Verify',
            ));

        $this->object->expects($this->exactly(2))
            ->method('_debug');

        $request->expects($this->once())
            ->method('asXml')
            ->with(true);

        $request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $response->expects($this->once())
            ->method('handle')
            ->will($this->returnValue(true));

        $this->object->expects($this->exactly(3))
            ->method('getRequest')
            ->will($this->returnValue($request));

        $this->object->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $this->object->expects($this->once())
            ->method('getConfigData')
            ->with('user_id')
            ->will($this->returnValue(1));

        $this->object->expects($this->once())
            ->method('getGatewayUrl')
            ->will($this->returnValue('http://example.com'));

        $this->assertTrue($this->object->verify($address));
    }
}
