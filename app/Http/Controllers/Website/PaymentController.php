<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Ixudra\Curl\Facades\Curl;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function phonePe(Request $request)
    {
        $isTestMode = false;
        $merchantId = $isTestMode ? "PGTESTPAYUAT" : "GLOBALSPACEONLINE";
        $saltKey = $isTestMode ? "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399" : "f5129377-8e73-45a5-b19d-07a7b4859850";
        $phonePeBaseUrl = $isTestMode ? "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay" : "https://api.phonepe.com/apis/hermes/pg/v1/pay";
        $amount = $request->amount * 100;
        $user_id = Auth::id();
        $trans_id = $user_id.'-'.uniqid();
        $data = array (
            'merchantId' => $merchantId,
            'merchantTransactionId' => $trans_id,
            'merchantUserId' => 'MUID123',
            'amount' => $amount,
            'redirectUrl' => route('response'),
            'redirectMode' => 'POST',
            'callbackUrl' => route('response'),
            'mobileNumber' => '9921144845',
            'paymentInstrument' =>
                array (
                    'type' => 'PAY_PAGE',
                ),
        );

        $encode = base64_encode(json_encode($data));

        $saltIndex = 1;

        $string = $encode.'/pg/v1/pay'.$saltKey;
        $sha256 = hash('sha256',$string);

        $finalXHeader = $sha256.'###'.$saltIndex;

        $response = Curl::to($phonePeBaseUrl)
            ->withHeader('Content-Type:application/json')
            ->withHeader('X-VERIFY:'.$finalXHeader)
            ->withData(json_encode(['request' => $encode]))
            ->post();

        $rData = json_decode($response);

        return redirect()->to($rData->data->instrumentResponse->redirectInfo->url);

    }

    public function response(Request $request)
    {

        try {

            $input = $request->all();

            $saltKey = 'f5129377-8e73-45a5-b19d-07a7b4859850';
            $saltIndex = 1;

            $finalXHeader = hash('sha256','/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'].$saltKey).'###'.$saltIndex;

            $response = Curl::to('https://api.phonepe.com/apis/hermes/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'])
                ->withHeader('Content-Type:application/json')
                ->withHeader('accept:application/json')
                ->withHeader('X-VERIFY:'.$finalXHeader)
                ->withHeader('X-MERCHANT-ID:'.$input['transactionId'])
                ->get();

            $response = json_decode($response);

            DB::beginTransaction();

            if ($response->success) {
                $getUserId = explode('-', $response->data->merchantTransactionId);
                $user_id = $getUserId[0];
                $amount = $response->data->amount / 100;
                $transaction = new Transaction();
                $transaction->user_id = $user_id;
                $transaction->points = $amount;
                $transaction->transaction_type = 'deposit';
                $transaction->transaction_creator = 'user';
                $transaction->status = 'complete';
                $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
                $transaction->save();
                if ($transaction) {
                    $user = User::find($user_id);
                    $user->points += $amount;
                    $user->save();
                    if ($user) {
                        $output = ['success' => true, 'msg' => 'Points Updated Successfully.'];
                    } else {
                        $output = ['success' => false, 'msg' => 'Something Went Wrong.'];
                    }
                }
            } else {
                $output = ['success' => false, 'msg' => $response->message];
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('home')->with('status', $output);
    }

    // PayU Payment Method Integrations

    public function payUMoney(Request $request) {
        $isTestMode = false; // Set to true for testing, false for production
        $merchantKey = $isTestMode ? "fb9u98" : "V1A5CvPq";
        $salt = $isTestMode ? "pWIcDzFh1LqAGnSF4EtfHbjJnxn87aiR" : "XUwnmHsibO";
        $payuBaseUrl = $isTestMode ? "https://test.payu.in" : "https://secure.payu.in";

        $name = Auth::user()->name;
        $email = Auth::user()->email;
        $phone = Auth::user()->phone;
        $user_id = Auth::user()->id;
        $amount = $request->amount;
        $product_info = 'RK Online';
        $service_provider = '';
        $successURL = route('pay.u.response');
        $failURL = route('pay.u.cancel');

        $action = $payuBaseUrl . '/_payment';
        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $posted = [
            'key' => $merchantKey,
            'txnid' => $txnid,
            'amount' => $amount,
            'productinfo' => $product_info,
            'firstname' => $name,
            'email' => $email,
            'udf1' => $user_id,
        ];
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        $hashVarsSeq = explode('|', $hashSequence);
        $hashString = '';
        foreach ($hashVarsSeq as $hashVar) {
            $hashString .= isset($posted[$hashVar]) ? $posted[$hashVar] : '';
            $hashString .= '|';
        }
        $hashString .= $salt;

        // Calculate hash
        $hash = strtolower(hash('sha512', $hashString));

        $responseData = [
            'action' => $action,
            'hash' => $hash,
            'merchant_key' => $merchantKey,
            'txnid' => $txnid,
            'successURL' => $successURL,
            'failURL' => $failURL,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'product_info' => $product_info,
            'service_provider' => $service_provider,
            'amount' => $amount,
            'user_id' => $user_id,
        ];

        return response()->json($responseData);
    }

    public function payUResponse(Request $request)
    {
        try {
            DB::beginTransaction();

            if ($request->status === 'success') {
                $user_id = $request->udf1;
                $transaction = new Transaction();
                $transaction->user_id = $user_id;
                $transaction->points = $request->amount;
                $transaction->transaction_type = 'deposit';
                $transaction->transaction_creator = 'user';
                $transaction->status = 'complete';
                $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
                $transaction->save();
                if ($transaction) {
                    $user = User::find($user_id);
                    $user->points += $request->amount;
                    $user->save();
                    if ($user) {
                        $output = ['success' => true, 'msg' => 'Points Updated Successfully.'];
                    } else {
                        $output = ['success' => false, 'msg' => 'Something Went Wrong'];
                    }
                }
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('home')->with('status', $output);
    }

    public function payUCancel(Request $request)
    {
        $output = ['success' => false, 'msg' => 'Payment cancelled try again later.'];
        return redirect()->route('home')->with('status', $output);
    }

    public function createOrder(Request $request)
    {
        $setting = Setting::first();
        $api = new Api($setting->razorpay_key_id, $setting->razorpay_secret_key);
        $order = $api->order->create([
            'receipt'         => 'order_rcptid_11',
            'amount'          => $request->amount * 100, // amount in the smallest currency unit
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ]);

        return response()->json(['order_id' => $order['id']]);
    }

    public function verifyPayment(Request $request)
    {
        $setting = Setting::first();
        $api = new Api($setting->razorpay_key_id, $setting->razorpay_secret_key);
        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature($attributes);

            DB::beginTransaction();

            $user_id = Auth::id();
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->points = $request->amount / 100; // convert back to original amount
            $transaction->transaction_type = 'deposit';
            $transaction->transaction_creator = 'user';
            $transaction->status = 'complete';
            $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
            $transaction->save();

            if ($transaction) {
                $user = User::find($user_id);
                $user->points += $request->amount / 100; // convert back to original amount
                $user->save();

                if ($user) {
                    $output = ['success' => true, 'msg' => 'Points Updated Successfully.', 'points' => $user->points];
                } else {
                    $output = ['success' => false, 'msg' => 'Something Went Wrong'];
                }
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }

        return response()->json($output);
    }

    // SBI Fast UPI Payment Integrations
    public function fastUPICreateOrder(Request $request)
    {
        $randomOrderId = 'ORD' . time() . rand(100000, 999999);
        $user = Auth::user();

        $data = [
            "token" => "7493ce-d99950-3cfb72-4a82a6-5c1170", // Replace with your API TOKEN
            "order_id" => $randomOrderId,
            "txn_amount" => $request->amount,
            "txn_note" => 'Account Top Up',
            "product_name" => "DP ON WEB",
            "customer_name" => $user->name,
            "customer_mobile" => '9011511707',
            "customer_email" => $user->email,
            "callback_url" => route('fastUPI.payment.callback')
        ];

        $response = Http::post('https://sbi.fastupi.io/order/create', $data);

        if ($response->successful() && $response->json()['status'] == "true") {
            // Store the order ID and user ID in a database table
            DB::table('payment_orders')->insert([
                'order_id' => $randomOrderId,
                'user_id' => $user->id,
                'amount' => $request->amount,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return redirect($response->json()['results']['payment_url']);
        }

        $output = ['success' => false, 'msg' => $response->json()['message']];
        return redirect()->back()->with('status', $output);
    }

    public function fastUPICallback(Request $request)
    {
        $data = [
            "token" => "7493ce-d99950-3cfb72-4a82a6-5c1170", // Replace with your API TOKEN
            "order_id" => $request->order_id
        ];

        $response = Http::post('https://sbi.fastupi.io/order/status', $data);
        try {
            DB::beginTransaction();

            if ($response->successful() && $response->json()['status']) {
                // Retrieve the user_id and amount using the order_id
                $order = DB::table('payment_orders')->where('order_id', $request->order_id)->first();
                if (!$order) {
                    throw new \Exception('Order not found');
                }

                $user_id = $order->user_id;
                $amount = $order->amount;

                $transaction = new Transaction();
                $transaction->user_id = $user_id;
                $transaction->points = $amount;
                $transaction->transaction_type = 'deposit';
                $transaction->transaction_creator = 'user';
                $transaction->status = 'complete';
                $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
                $transaction->save();
                if ($transaction) {
                    $user = User::find($user_id);
                    $user->points += $amount;
                    $user->save();
                    if ($user) {
                        $output = ['success' => true, 'msg' => 'Points Updated Successfully.'];
                    } else {
                        $output = ['success' => false, 'msg' => 'Something Went Wrong'];
                    }
                }
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('home')->with('status', $output);
    }
}
