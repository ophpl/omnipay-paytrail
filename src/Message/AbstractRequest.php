<?php

namespace Omnipay\Paytrail\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Get merchant id.
     *
     * @return string merchantId
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set merchant id.
     *
     * @param string $value merchantId
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
     * Get locale.
     *
     * @return string locale
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * Set locale.
     *
     * @param string $value locale
     *
     * @return $this
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * Get language, if not set fallback to locale.
     *
     * @return string language
     */
    public function getLanguage()
    {
        $language = $this->getParameter('language');

        if (empty($language)) {
            $locale = $this->getLocale();

            if (empty($locale)) {
                return "";
            }

            // convert to IETF locale tag if other style is provided and then get first part, primary language
            $language = strtok(str_replace('_', '-', $locale), '-');
        }

        return strtoupper($language);
    }

    /**
     * Set language.
     *
     * @param string $value language
     *
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }
}
