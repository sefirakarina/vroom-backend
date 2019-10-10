<?php

namespace App\Http\Controllers;

use App\Booking;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Car;
use Illuminate\Support\Facades\Date;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

//ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

class PaymentController extends Controller
{

    public function __construct()
    {

       //$this->middleware('auth:api');
    }

    public function createPayment(/*Request $request*/){

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AdApCM-7CByQYAmuo6jaX5sW5O028yrVDx_LrBoet3LQJfTaGBZe5w3vHUqjelS8oGr2VWtUwN8_KV-a',     // ClientID
                'EOn0VKnOJFDmF9fX_EqQvkRWHrd0U_GtcrwWVA-FBcNY_OH1Cjn5_5EDQsJAM1Pthwm5vG_3d8c0uaHA'      // ClientSecret
            )
        );

//        $car_id = $_GET['car_id'] ;
//        $rentDays = $_GET['rentDays'] ;
//        $user_id = $_GET['user_id'] ;
        $booking_id = $_GET['booking_id'] ;

        $booking = Booking::where('id', $booking_id)
            ->select('*')
            ->first();


        $car = Car::where('id', $booking->car_id)
            ->select('type', 'price_per_day')
            ->first();

        $datetime1 = explode(" ", $booking->begin_time);
        $datetime2 = explode(" ", $booking->return_time);

        $startTimeStamp = strtotime($datetime1[0]);
        $endTimeStamp = strtotime($datetime2[0]);

        $timeDiff = abs($endTimeStamp - $startTimeStamp);

        $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        // and you might want to convert to integer
        $diff = intval($numberDays);

//        $date1 = new \DateTime($datetime1[0]);
//        $date2 = new \DateTime($datetime2[0]);
//
//        $diff = date_diff($date1,$date2);

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName($car->type)
                ->setCurrency('AUD')
                ->setQuantity($diff)
                ->setSku($booking->car_id) // Similar to `item_number` in Classic API
                ->setPrice($car->price_per_day);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $details = new Details();
        $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal($car->price_per_day * $diff);

        $amount = new Amount();
        $amount->setCurrency("AUD")
                ->setTotal($car->price_per_day * $diff)
                ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription("Payment description")
                    ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls   ->setReturnUrl("https://powerful-sea-28932.herokuapp.com/api/payment/execute/?booking_id=" . $booking_id)
                        ->setCancelUrl('https://vroom-frontend.herokuapp.com/?booking_id='. $booking_id. '&payment_status=CANCELED');

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);
            //return response()->json(['error' => $diff], 417);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {

            return response()->json(['error' => $ex->getData()], 417);
        }

        return redirect($payment->getApprovalLink());
    }


    public function execute()
    {

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AdApCM-7CByQYAmuo6jaX5sW5O028yrVDx_LrBoet3LQJfTaGBZe5w3vHUqjelS8oGr2VWtUwN8_KV-a',     // ClientID
                'EOn0VKnOJFDmF9fX_EqQvkRWHrd0U_GtcrwWVA-FBcNY_OH1Cjn5_5EDQsJAM1Pthwm5vG_3d8c0uaHA'      // ClientSecret
            )
        );

        // Get payment object by passing paymentId
        $paymentId = $_GET['paymentId'];
        $payment = Payment::get($paymentId, $apiContext);
        $payerId = $_GET['PayerID'];

        $booking_id = $_GET['booking_id'];

        // Execute payment with payer ID
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Execute payment
            $result = $payment->execute($execution, $apiContext);
            //$this->determineBookingStatus(json_decode($result)->payer->status, $booking_id);
            //var_dump($result);

            $status= json_decode($result)->payer->status;

            return redirect()->away('https://powerful-sea-28932.herokuapp.com/api/payment/determineBookingStatus/?payment_status='.
                                    $status .  '&booking_id=' . $booking_id);
            //return response()->json(json_decode($result), 200);

        } catch (PayPal\Exception\PayPalConnectionException $ex) {

            //return response()->json(['error' => $ex->getData()], 500);
//            echo $ex->getCode();
//            echo $ex->getData();
//            die($ex);
            return redirect()->away('https://vroom-frontend.herokuapp.com/?booking_id='. $booking_id. '&payment_status=UNAVAILABLE');
        } catch (Exception $ex) {
            //return response()->json(['error' => $ex], 500);
            return redirect()->away('https://vroom-frontend.herokuapp.com/?booking_id='. $booking_id. '&payment_status=UNAVAILABLE');
        }

    }

    public function determineBookingStatus(){

        $payment_status = $_GET['payment_status'];

        $booking_id = $_GET['booking_id'];

        if($payment_status == "VERIFIED"){

            try{

                Booking::where('id', $booking_id)->update ([

                    'payment_status' => true
                ]);
                return redirect()->away('https://vroom-frontend.herokuapp.com/?booking_id='. $booking_id. '&payment_status='. $payment_status);

            }catch (\Exception $e){
                return redirect()->away('https://vroom-frontend.herokuapp.com/?booking_id='. $booking_id. '&payment_status='. $payment_status);
            }

        }
        else
            return redirect()->away('https://vroom-frontend.herokuapp.com/?booking_id='. $booking_id. '&payment_status='. $payment_status);
    }

}
