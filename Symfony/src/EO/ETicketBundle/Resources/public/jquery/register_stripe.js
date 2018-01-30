'use strict';

var stripe = Stripe('pk_test_6pRNASCoBOKtIshFeQd4XMUh');

function stripeTokenHandler(token, form) {

    // Insert the token ID into the form so it gets submitted to the server
    //var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    // Submit the form
    form.submit();
}

function registerElements(elementsBySelector, container) {
    var formClass = '.' + container;
    var formContainer = document.querySelector(formClass);

    var form = formContainer.querySelector('form');
    var button = form.querySelector('button[type="submit"]');

    function enableSubmitButton() {
        button.removeAttribute('disabled');
    }

    function disableSubmitButton() {
        button.setAttribute('disabled', 'true');
    }

    disableSubmitButton();

    // Listen for errors from each Element, and show error messages in the UI.
    Object.keys(elementsBySelector).forEach(function (selector) {
        var element = elementsBySelector[selector];
        element.on('change', function (event) {
            var $selectorParent = $(selector).parent();
            var $message = $selectorParent.find('.message');

            if (event.error) {
                $message.text(event.error.message);
                $message.show();
                disableSubmitButton();
            } else {
                $message.text("");
                $message.hide();
                enableSubmitButton();
            }
        });
    });


    // Listen on the form's 'submit' handler...
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        disableSubmitButton();
        // Use Stripe.js to create a token. We only need to pass in one Element
        // from the Element group in order to create a token. We can also pass
        // in the additional customer data we collected in our form.
        stripe.createToken(elementsBySelector['#ticket-card-number']).then(function(result) {
            // Stop loading!
            //example.classList.remove('submitting');

            if (result.token) {
                // If we received a token, show the token ID.
                stripeTokenHandler(result.token, form);
            }
        });
    });
}