<?php

namespace Omnipay\Paytrail\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Paytrail\SDK\Response\PaymentResponse;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Get the response data.
     *
     * @return PaymentResponse
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        // Return false to indicate that more actions are needed to complete the transaction.
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isRedirect()
    {
        return !empty($this->getData()->getHref());
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirectUrl()
    {
        return $this->getData()->getHref();
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionReference()
    {
        return $this->getData()->getTransactionId();
    }

}
