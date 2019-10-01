<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Car;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class PaymentController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');
    }

    public function createPayment($car_id){

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AdApCM-7CByQYAmuo6jaX5sW5O028yrVDx_LrBoet3LQJfTaGBZe5w3vHUqjelS8oGr2VWtUwN8_KV-a',     // ClientID
                'EOn0VKnOJFDmF9fX_EqQvkRWHrd0U_GtcrwWVA-FBcNY_OH1Cjn5_5EDQsJAM1Pthwm5vG_3d8c0uaHA'      // ClientSecret
            )
        );

        $car = Car::where('id', $car_id)
            ->select('type', 'price_per_day')
            ->first();

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName($car->type)
                ->setCurrency('AUD')
                ->setQuantity(1)
                ->setSku($car_id) // Similar to `item_number` in Classic API
                ->setPrice($car->price_per_day);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $details = new Details();
        $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal($car->price_per_day);

        $amount = new Amount();
        $amount->setCurrency("AUD")
                ->setTotal($car->price_per_day)
                ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription("Payment description")
                    ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls   ->setReturnUrl("http://localhost:8000/api/payment/execute")
                        ->setCancelUrl("https://vroom-frontend.herokuapp.com");

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json(['error' => $ex->getData()], 500);
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

        // Execute payment with payer ID
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Execute payment
            $result = $payment->execute($execution, $apiContext);
            var_dump($result);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }

    }
}
