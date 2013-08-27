<?php

/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Verifier_Usps_Response extends Varien_Object
{
    /**
     * Init response data
     *
     * @param Zend_Http_Response $response
     * @return $this
     */
    public function init(Zend_Http_Response $response)
    {
        $this->setBody($response->getBody());
        return $this;
    }

    /**
     * Get request instance
     *
     * @return Ecg_AddressVerification_Model_Verifier_Usps_Request
     */
    public function getRequest()
    {
        return Mage::getSingleton('ecg_addressverification/verifier_usps_request');
    }

    /**
     * Get helper instance
     *
     * @return Ecg_AddressVerification_Helper_Data
     */
    public function getHelper()
    {
        return Mage::helper('ecg_addressverification');
    }

    /**
     * Get XML object
     *
     * @return SimpleXMLElement
     */
    protected function _getXml()
    {
        return new SimpleXMLElement($this->getBody());
    }

    /**
     * Handle request data
     *
     * @throws Exception
     * @return bool
     */
    public function handle()
    {
        $xml = $this->_getXml();

        switch ($xml->getName()) {
            case 'Error' :
                throw new Ecg_AddressVerification_Exception($this->getHelper()->__('API error %s. Description: %s. Source: %s.',
                    $xml->Number,
                    $xml->Description,
                    $xml->Source
                ));
            case 'AddressValidateResponse' :
                if($xml->Address->Error) {
                    throw new Ecg_AddressVerification_Exception($this->getHelper()->__('API address validate response error %s. Description: %s. Source: %s.',
                        $xml->Address->Error->Number,
                        $xml->Address->Error->Description,
                        $xml->Address->Error->Source
                    ));
                } else {
                    $zip5 = $xml->Address[0]->Zip5;
                    if ($this->getRequest()->getZip5() != $zip5) {
                        Mage::throwException($this->getHelper()->__('Invalid zip code %s, should be %s.', $this->getRequest()->getZip5(), $zip5));
                    }
                    $state = $xml->Address[0]->State;
                    if ($this->getRequest()->getState() != $state) {
                        Mage::throwException($this->getHelper()->__('Invalid state %s, should be %s.', $this->getRequest()->getState(), $state));
                    }
                    return true;
                }
            default :
                throw new Ecg_AddressVerification_Exception($this->getHelper()->__('Invalid Response.'));
        }
    }
}
