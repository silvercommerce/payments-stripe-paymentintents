<?php

namespace SilverCommerce\StripePaymentIntents\Extensions;

use ReflectionProperty;
use SilverStripe\Core\Extension;

class PurchaseServiceExtension extends Extension
{
    public function onBeforeCompletePurchase(array &$data = [])
    {
        // Hack to get the payment, as silverstripe-omnipay doesn't currently
        // provide a getPayment() method in PaymentService
        $reflectionProperty = new ReflectionProperty(get_class($this->owner), 'payment');
        $reflectionProperty->setAccessible(true);
        $payment = $reflectionProperty->getValue($this->owner);
        if ($payment->StripePaymentIntentReference) {
            // Pass the Payment Intent reference with the transaction data to Stripe
            $data['paymentIntentReference'] = $payment->StripePaymentIntentReference;
        }
    }
}