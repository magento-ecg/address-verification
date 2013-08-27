<?php
/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Helper_DataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ecg_AddressVerification_Helper_Data
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Ecg_AddressVerification_Helper_Data();
    }

    /**
     * @covers Ecg_AddressVerification_Helper_Data::isVerificationEnabled
     */
    public function testIsVerificationEnabled()
    {
        $this->assertTrue($this->object->isVerificationEnabled());
    }

    /**
     * @covers Ecg_AddressVerification_Helper_Data::getVerifier
     */
    public function testGetVerifier()
    {
        $this->assertInstanceOf('Ecg_Addressverification_Model_Verifier', $this->object->getVerifier());
    }
}
