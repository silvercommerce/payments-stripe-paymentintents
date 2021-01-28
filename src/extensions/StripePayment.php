<?php

namespace Silvercommerce\StripePaymentIntents\Extensions;

use SilverStripe\ORM\DataExtension;

class StripePayment extends DataExtension
{
    private static $db = [
        'StripePaymentIntentReference' => 'Varchar(255)'
    ];
}