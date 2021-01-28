function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('Form_PaymentForm');
    var hiddenInput = document.getElementById('token');
    hiddenInput.setAttribute('value', token.id);
    form.submit();
}

window.onload = function() {
    // Create a Stripe client.
    var stripe = Stripe(stripe_pk);

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased'
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('Form_PaymentForm');
    var cardholderName = document.getElementById('cardholder-name');
    var cardholderEmail = document.getElementById('cardholder-email');
    var cardButton = document.getElementById('card-button');
    
    form.addEventListener('submit', function(ev) {
      ev.preventDefault();
      stripe.createPaymentMethod('card', card, {
        billing_details: {
            name: cardholderName.value,
            email: cardholderEmail.value
        }
      }).then(function(result) {
        if (result.error) {
          // Show error in payment form
        } else {
          // Otherwise send paymentMethod.id to your server (see Step 2)
          document.getElementById('paymentMethod').value = result.paymentMethod.id;
          form.submit();
        }
      });
    });
    
}