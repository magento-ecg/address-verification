<?php

/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Observer
{
    /**
     * Get helper
     *
     * @return Ecg_Addressverification_Helper_Data
     */
    public function getHelper()
    {
        return Mage::helper('ecg_addressverification');
    }

    /**
     * Perform address verification
     *
     * @param Varien_Event_Observer $observer
     */
    public function verifyAddress(Varien_Event_Observer $observer)
    {
        if (!$this->getHelper()->isVerificationEnabled()) {
            return;
        }

        /** @var Mage_Customer_Model_Address $address */
        $address = $observer->getDataObject();

        $this->getHelper()->getVerifier()->verify($address);
    }
}
