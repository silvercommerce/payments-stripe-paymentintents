# SilverCommerce Stripe Payment Intents

Module that adds Stripe Payments via an integrated payment form and
payment intents to a SilverCommerce site (using the omnipay stripe gateway).

## Installation

Install via composer:

    composer require silvercommerce/payments-stripe-paymentintents

## Configuration

Configure how you would any SilverStripe omnipay modules:

_payments.yml_

    ---
    Name: paymentconfig
    ---
    SilverStripe\Omnipay\Model\Payment:
      allowed_gateways:
        - 'Stripe_PaymentIntents'

    SilverStripe\Omnipay\GatewayInfo:
      PayPal_Express:
        parameters:
            apikey: sk_live_xxxxxxxxx
            publishableKey: pk_live_xxxxxxxxx

    # Config for test environments
    ---
    Except:
    environment: 'live'
    ---
    SilverStripe\Omnipay\GatewayInfo:
      Stripe_PaymentIntents:
        parameters:
            apikey: sk_test_xxxxxx
            publishableKey: pk_test_xxxxxx

## Payment Intents

In order to comply with SCA regulations in the EU, this module uses
the payment intents API to generate a payment intent prior to completing
a purchase.

This means that a payment will also have a payment intent ID saved
against it in the database.

The JavaScript to handle generating the payment intent is added
automatically and adds the payment intent ID to the omnipay gateway
form