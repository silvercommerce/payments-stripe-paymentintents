<?php

namespace SilverCommerce\StripePaymentIntents\Extensions;

use Exception;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\HiddenField;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Omnipay\GatewayInfo;

class StripeCheckout extends Extension
{
    /**
     * Is the current gateway stripe?
     * 
     * @return boolean
     */
    public function isStripe()
    {
        $gateway = $this->owner->getPaymentMethod();
        $stripe = ($gateway == "Stripe" || $gateway == 'Stripe_PaymentIntents');
        return $stripe;
    }

    public function updatePaymentForm($form)
    {
        $fields = $form->Fields();

        if ($this->owner->isStripe()) {
            $gateway = $this->owner->getPaymentMethod();
            $stripeInfo = Config::inst()->get(GatewayInfo::class, $gateway);
            $estimate = $this->getOwner()->getEstimate();

            if (!$stripeInfo || !isset($stripeInfo['parameters']) || !isset($stripeInfo['parameters']['publishableKey'])) {
                throw new Exception($gateway.' payment configuration missing');
            }

            $publishableKey = $stripeInfo['parameters']['publishableKey'];
            Requirements::javascript('https://js.stripe.com/v3/');
            Requirements::javascript("silvercommerce/payments-stripe-paymentintents:client/dist/js/stripe.js");
            Requirements::customScript("var stripe_pk = '{$publishableKey}'");

            $fields = $form->Fields();
            foreach ($fields as $field) {
                $fields->remove($field);
            }

            $stripe_fields = LiteralField::create(
                'StripeFields',
                $this->getOwner()->renderWith('SilverCommerce\StripePaymentIntents\StripeCheckout_PaymentFields')
            );

            $fields->push(HiddenField::create('token'));
            $fields->push(
                HiddenField::create('cardholder-name')->setValue($estimate->DeliveryFirstName.' '.$estimate->DeliverySurname)
            );
            $fields->push(
                HiddenField::create('cardholder-email')->setValue($estimate->Email)
            );
            $fields->push(
                HiddenField::create('cardholder-lineone')->setValue($estimate->Address1)
            );
            $fields->push(
                HiddenField::create('cardholder-zip')->setValue($estimate->PostCode)
            );
            $fields->push(
                HiddenField::create("paymentMethod")
            );
            $fields->push($stripe_fields);
        }
    }

    public function onBeforeSubmit($payment, $order, &$omnipay_data)
    {
        // Set a description for this payment
        $omnipay_data['confirm'] = true;
    }

    public function onBeforeRedirect(&$payment, $order, &$response)
    {
        if ($response->isRedirect()) {
            $omnipayResponse = $response->getOmnipayResponse();
            if (is_a($omnipayResponse, 'Omnipay\Stripe\Message\PaymentIntents\Response')) {
                // Store the Payment Intent reference for later...
                $payment->StripePaymentIntentReference = $omnipayResponse->getPaymentIntentReference();
                $payment->write();
            }
        }
    }
}