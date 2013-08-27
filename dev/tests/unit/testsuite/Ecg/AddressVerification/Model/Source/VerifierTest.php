<?php
/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Source_VerifierTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ecg_AddressVerification_Model_Source_Verifier
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Ecg_AddressVerification_Model_Source_Verifier();
    }

    /**
     * @covers Ecg_AddressVerification_Model_Source_Verifier::getUsps
     */
    public function testGetUsps()
    {
        $this->assertInstanceOf('Ecg_AddressVerification_Model_Verifier_Usps', $this->object->getUsps());
    }

    /**
     * @covers Ecg_AddressVerification_Model_Source_Verifier::toOptionArray
     */
    public function testToOptionArray()
    {
        $usps = new Ecg_AddressVerification_Model_Verifier_Usps();
        $this->assertEquals(array($usps->getCode() => $usps->getLabel()), $this->object->toOptionArray());
    }
}
