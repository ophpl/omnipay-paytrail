<?php

namespace Omnipay\Paytrail\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Paytrail\SDK\Client;
use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Model\Address;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\Item;
use Paytrail\SDK\Request\PaymentRequest;

class PurchaseRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'transactionId',
            'description',
            'returnUrl',
            'cancelUrl',
            'notifyUrl'
        );

        $payment = new PaymentRequest();
        $payment->setStamp(hash('sha256', hrtime(true)));
        $payment->setAmount($this->getAmountInteger());
        $payment->setReference($this->getTransactionId());
        $payment->setCurrency($this->getCurrency());
        $payment->setRedirectUrls($this->createCallbackUrl($this->getReturnUrl(), $this->getCancelUrl()));
        $payment->setCallbackUrls($this->createCallbackUrl($this->getNotifyUrl(), $this->getNotifyUrl()));
        $payment->setLanguage($this->getLanguage());

        $items = $this->getItems();

        if (!empty($items)) {
            $payment->setItems(array_map(
                function ($item) {
                    /** @var \Omnipay\Common\Item $item */
                    $orderItem = new Item();

                    // TODO move price to use Money class with currency
                    $orderItem->setUnitPrice($this->getAmountInMinorUnits($item->getPrice(), $this->getCurrency()))
                        ->setUnits($item->getQuantity())
                        ->setDescription(trim(sprintf("%s %s", $item->getName(), $item->getDescription())))
                        ->setProductCode($item->getName());

                    return $orderItem;
                },
                $items->all()
            ));
        }

        $card = $this->getCard();

        if (empty($card)) {
            return $payment;
        }

        // Set customer data, email is required field
        if (!empty($card->getEmail())) {
            $customer = (new Customer())
                ->setEmail($card->getEmail())
                ->setFirstName($card->getFirstName())
                ->setLastName($card->getLastName())
                ->setPhone($card->getPhone());

            $payment->setCustomer($customer);
        }

        // set optional invoicing address
        $invoicingAddress = (new Address())
            ->setStreetAddress($card->getBillingAddress1())
            ->setPostalCode($card->getBillingPostcode())
            ->setCity($card->getBillingCity())
            ->setCounty($card->getBillingState())
            ->setCountry($card->getBillingCountry());

        try {
            $invoicingAddress->validate();
            $payment->setInvoicingAddress($invoicingAddress);
        } catch (ValidationException $e) {
            // if address validation failed and it will not be sent
        }

        // set optional delivery address
        $deliveryAddress = (new Address())
            ->setStreetAddress($card->getShippingAddress1())
            ->setPostalCode($card->getShippingPostcode())
            ->setCity($card->getShippingCity())
            ->setCounty($card->getShippingState())
            ->setCountry($card->getShippingCountry());

        try {
            $deliveryAddress->validate();
            $payment->setDeliveryAddress($deliveryAddress);
        } catch (ValidationException $e) {
            // if address validation failed and it will not be sent
        }

        return $payment;
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        try {
            $client = new Client($this->getMerchantId(), $this->getSecretKey(), $this->getPlatformName());
            $response = $client->createPayment($data);
            return $this->response = new PurchaseResponse($this, $response);
        } catch (\Throwable $e) {
            throw new InvalidRequestException('Failed to request purchase: ' . $e->getMessage(), 0, $e);
        }
    }

    protected function getAmountInMinorUnits($amount, $currency) {
        switch ($currency) {
            case 'EUR':
                return $amount * 100;
            default:
                throw new \InvalidArgumentException($currency . ' minor units are not supported by library');
        }
    }

    protected function createCallbackUrl($successUrl, $cancelUrl) {
        $callback = new CallbackUrl();

        $callback->setSuccess($successUrl);
        $callback->setCancel($cancelUrl);

        return $callback;
    }
}
