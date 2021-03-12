<div class="card">
    <div class="card-body">
        <div class="row pt-2 pb-4">
            <div class="field col-12">
                <div id="stripe-card-number" class="stripe-field-container border-bottom"></div>
                <label for="stripe-card-number"><%t StripePaymentIntents.CardNumber "Card number" %></label>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div id="stripe-card-expiry" class="stripe-field-container border-bottom"></div>
                <label for="stripe-card-expiry"><%t StripePaymentIntents.Expiration "Expiration" %></label>
            </div>
            <div class="col-6">
                <div id="stripe-card-cvc" class="stripe-field-container border-bottom"></div>
                <label for="stripe-card-cvc"><%t StripePaymentIntents.CVC "CVC" %></label>
            </div>
        </div>
    </div>
</div>