<?php

/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Verifier_Usps extends Ecg_AddressVerification_Model_Verifier
{
    /**
     * USPS API code
     */
    const API_CODE_VERIFY = 'Verify';

    /**
     * Service provider code
     *
     * @var string
     */
    protected $_code = 'usps';

    /**
     * Service provider label
     *
     * @var string
     */
    protected $_label = 'USPS Web Tools API';

    /**
     * Get request object
     *
     * @return Ecg_AddressVerification_Model_Verifier_Usps_Request
     */
    public function getRequest()
    {
        return Mage::getSingleton('ecg_addressverification/verifier_usps_request');
    }

    /**
     * Get response object
     *
     * @return Ecg_AddressVerification_Model_Verifier_Usps_Response
     */
    public function getResponse()
    {
        return Mage::getSingleton('ecg_addressverification/verifier_usps_response');
    }

    /**
     * Run verification process
     *
     * @param Mage_Customer_Model_Address $address
     * @return bool
     */
    public function verify(Mage_Customer_Model_Address $address)
    {
        $this->getRequest()->init($address, array(
            'user_id'     => $this->getConfigData('user_id'),
            'gateway_url' => $this->getGatewayUrl(),
            'api_code'    => self::API_CODE_VERIFY
        ));

        $this->_debug(array('request' => $this->getRequest()->asXml(true)));
        $response = $this->getRequest()->send();
        $this->_debug(array('response' => $response->getBody()));

        try {
            return $this->getResponse()->handle();
        } catch (Ecg_AddressVerification_Exception $e) {
            Mage::logException($e);
        }

        return false;
    }
}
