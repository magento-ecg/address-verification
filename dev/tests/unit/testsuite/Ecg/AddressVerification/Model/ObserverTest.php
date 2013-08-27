<?php
/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_ObserverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ecg_AddressVerification_Model_Observer
     */
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMock('Ecg_AddressVerification_Model_Observer', array('getHelper'));
    }

    /**
     * @covers Ecg_AddressVerification_Model_Observer::getHelper
     */
    public function testGetHelper()
    {
        $object = new Ecg_AddressVerification_Model_Observer();
        $this->assertInstanceOf('Ecg_Addressverification_Helper_Data', $object->getHelper());
    }

    /**
     * @covers Ecg_AddressVerification_Model_Observer::verifyAddress
     */
    public function testVerifyAddress()
    {
        $observer = new Varien_Event_Observer();
        $address  = new Mage_Customer_Model_Address();

        $verifier = $this->getMock('Ecg_AddressVerification_Model_Verifier', array('verify'));
        $helper   = $this->getMock('Ecg_Addressverification_Helper_Data', array('isVerificationEnabled', 'getVerifier'));

        $helper->expects($this->once())
            ->method('isVerificationEnabled')
            ->will($this->returnValue(true));

        $helper->expects($this->once())
            ->method('getVerifier')
            ->will($this->returnValue($verifier));

        $verifier->expects($this->once())
            ->method('verify')
            ->with($address);

        $this->object->expects($this->exactly(2))
            ->method('getHelper')
            ->will($this->returnValue($helper));

        $observer->setDataObject($address);
        $this->object->verifyAddress($observer);
    }
}
