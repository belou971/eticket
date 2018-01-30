<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 15/01/18
 * Time: 12:46
 */

namespace EO\ETicketBundle\Payments;

//require_once('vendor/autoload.php');

use EO\ETicketBundle\Entity\Booking;

use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Error\Base;

class StripeCheckout
{

    const SECRET_KEY = "sk_test_BQokikJOvBiI2HlWgH4olfQ2";
    //const PUBLISHABLE_KEY ="pk_test_6pRNASCoBOKtIshFeQd4XMUh";

    public static function charge($token, Booking $data, $tax) {
        Stripe::setApiKey(self::SECRET_KEY);

        //Create a customer
        $customer = Customer::create( array('email' => $data->getEmail(),
                                            'source' => $token));

        $amount = $data->computeInvoice($tax) * 100;
        try {
                $charge = Charge::create(array('customer' => $customer->id,
                                               'amount' => $amount,
                                               'currency' => 'eur'));

                $chargeSerialize = $charge->jsonSerialize();

                $respondCharge = array('paid' => $charge->paid, 'outcome' => $chargeSerialize['outcome']);
        }
        catch (Base $ex) {
            $expBody = $ex->getJsonBody();
            $error  = $expBody['error'];

            $respondCharge = array('paid' => false, 'error' => $error);
        }

        return $respondCharge;
    }
}



