<?php

/**
 * (c) Magento ECG team <consulting@magento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Ecg_AddressVerification_Model_Verifier_Usps_Request extends Varien_Object
{
    /**
     * Request config params
     */
    const REQUEST_MAX_REDIRECTS = 0;
    const REQUEST_TIMEOUT       = 30;

    /**
     * Get core countries model
     *
     * @return Mage_Directory_Model_Country
     */
    protected function _getCountriesSingleton()
    {
        return Mage::getSingleton('directory/country');
    }

    /**
     * @return Ecg_AddressVerification_Model_Verifier_Usps_Response
     */
    public function getResponse()
    {
        return Mage::getSingleton('ecg_addressverification/verifier_usps_response');
    }

    /**
     * Get state code (CA, NY, etc) by ID
     *
     * @param int $regionId
     * @return string
     */
    protected function _getStateById($regionId)
    {
        return $this->_getCountriesSingleton()
            ->getRegions()
            ->getItemById($regionId)
            ->getCode();
    }

    /**
     * Initialize data from address object and config array
     *
     * @param Mage_Customer_Model_Address $address
     * @param array $config
     * @return Ecg_AddressVerification_Model_Verifier_Usps_Request
     */
    public function init(Mage_Customer_Model_Address $address, array $config)
    {
        $street = is_array($address->getStreet()) ? join(' ', $address->getStreet()) : $address->getStreet();
        $state  = $this->_getStateById($address->getRegionId());
        $zip5   = strlen($address->getPostcode()) == 5 ? $address->getPostcode() : '';
        $zip4   = strlen($address->getPostcode()) == 4 ? $address->getPostcode() : '';

        $this->setFirmName($address->getCompany())
            ->setAddress1('')
            ->setAddress2($street)
            ->setCity($address->getCity())
            ->setState($state)
            ->setZip5($zip5)
            ->setZip4($zip4)
            ->addData($config);

        return $this;
    }

    /**
     * Get request data as XML
     *
     * @param bool $indent
     * @return string
     */
    public function asXml($indent = false)
    {
        $out = new XMLWriter();
        $out->openMemory();
        if ($indent) {
            $out->setIndent(true);
        }
        $out->startDocument('1.0', 'UTF-8');
        $out->startElement('AddressValidateRequest');
        $out->writeAttribute('USERID', $this->getUserId());
        $out->startElement('Address');
        $out->writeElement('FirmName', $this->getFirmName());
        $out->writeElement('Address1', '');
        $out->writeElement('Address2', $this->getAddress2());
        $out->writeElement('City', $this->getCity());
        $out->writeElement('State', $this->getState());
        $out->writeElement('Zip5', $this->getZip5());
        $out->writeElement('Zip4', $this->getZip4());
        $out->endElement();
        $out->endElement();

        return $out->flush();
    }

    /**
     * Send request to USPS server
     *
     * @return Ecg_AddressVerification_Model_Verifier_Usps_Response
     */
    public function send()
    {
        $client = new Zend_Http_Client();
        $client->setUri($this->getGatewayUrl())
            ->setConfig(array('maxredirects' => self::REQUEST_MAX_REDIRECTS, 'timeout' => self::REQUEST_TIMEOUT))
            ->setParameterGet('API', $this->getApiCode())
            ->setParameterGet('XML', $this->asXml());

        return $this->getResponse()->init($client->request());
    }
}
