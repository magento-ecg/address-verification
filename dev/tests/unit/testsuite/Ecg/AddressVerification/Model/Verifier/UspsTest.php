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
        //$object = $this->getMock('Ecg_AddressVerification_Model_Verifier_Usps', array('getConfigData', 'getGatewayUrl'))
        //$address = new Mage_Customer_Model_Address();
    }
}
