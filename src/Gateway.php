<?php

namespace Omnipay\Paytrail;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Paytrail\Message\PurchaseRequest;

/**
 * Class Gateway
 * https://docs.paytrail.com/
 * https://github.com/paytrail/paytrail-php-sdk
 * @package Omnipay\Paytrail
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Paytrail';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'merchantId'   => 0,
            'secretKey'    => '',
            'platformName' => 'omnipay-paytrail',
            'testMode'     => false
        );
    }

    /**
     * Get merchant id.
     *
     * @return int merchantId
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set merchant id.
     *
     * @param int $value merchantId
     *
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get merchant secret key.
     *
     * @return string secret key
     */
    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    /**
     * Set merchant secret key.
     *
     * @param string $value secret key
     *
     * @return $this
     */
    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    /**
     * Get platform name.
     *
     * @return string platform name
     */
    public function getPlatformName()
    {
        return $this->getParameter('platformName');
    }

    /**
     * Set platform name.
     *
     * @param string $value platform name
     *
     * @return $this
     */
    public function setPlatformName($value)
    {
        return $this->setParameter('platformName', $value);
    }

    /**
     * @param array $parameters
     *
     * @return AbstractRequest|PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paytrail\Message\PurchaseRequest', $parameters);
    }
}
