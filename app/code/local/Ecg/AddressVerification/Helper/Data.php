<?php

/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Config XML path constants
     */
    const XML_PATH_ENABLE               = 'address_verification/general/enable';
    const XML_PATH_USE_SERVICE_PROVIDER = 'address_verification/general/use_service_provider';

    /**
     * Check iv address verification is enabled
     *
     * @return bool
     */
    public function isVerificationEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLE);
    }

    /**
     * Get address verification model
     *
     * @return Ecg_Addressverification_Model_Verifier
     */
    public function getVerifier()
    {
        return Mage::getSingleton('ecg_addressverification/verifier_' . Mage::getStoreConfig(self::XML_PATH_USE_SERVICE_PROVIDER));
    }
}
