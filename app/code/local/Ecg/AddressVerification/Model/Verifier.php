<?php

/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

abstract class Ecg_AddressVerification_Model_Verifier extends Varien_Object
{
    /**
     * Service provider code
     *
     * @var string
     */
    protected $_code;

    /**
     * Service provider label
     *
     * @var string
     */
    protected $_label;

    /**
     * Secure URL flag
     *
     * @var bool
     */
    protected $_useSecureUrl = true;

    /**
     * Get helper instance
     *
     * @return Ecg_Addressverification_Helper_Data
     */
    public function getHelper()
    {
        return Mage::helper('ecg_addressverification');
    }

    /**
     * Retrieve configuration data
     *
     * @param string $field
     * @return string
     */
    public function getConfigData($field)
    {
        return Mage::getStoreConfig('address_verification/' . $this->_code . '/' . $field);
    }

    /**
     * Retrieve gateway URL config value
     *
     * @return string
     */
    public function getGatewayUrl()
    {
        return $this->_useSecureUrl ? $this->getConfigData('secure_gateway_url') : $this->getConfigData('gateway_url');
    }

    /**
     * Log debug data to file
     *
     * @param mixed $debugData
     */
    protected function _debug($debugData)
    {
        if ($this->getConfigData('debug')) {
            Mage::getModel('core/log_adapter', 'address_verification_' . $this->_code . '.log')->log($debugData);
        }
    }

    /**
     * Get verifier code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Get verifier label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * Verify address
     *
     * @param Mage_Customer_Model_Address $address
     * @return mixed
     */
    abstract public function verify(Mage_Customer_Model_Address $address);
}
