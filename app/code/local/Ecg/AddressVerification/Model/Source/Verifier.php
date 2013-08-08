<?php

/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Source_Verifier
{
    /**
     * Get verifier model
     *
     * @return Ecg_AddressVerification_Model_Verifier_Usps
     */
    public function getUsps()
    {
        return Mage::getSingleton('ecg_addressverification/verifier_usps');
    }

    /**
     * Get list of available verification providers as array of address options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array($this->getUsps()->getCode() => $this->getUsps()->getLabel());
    }
}
