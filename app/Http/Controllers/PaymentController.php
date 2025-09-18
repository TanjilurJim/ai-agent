<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function payment(Request $request)
{
    $user_id = $request->input('user_id');
    $plan_id = $request->input('plan_id');
    $amount = $request->input('amount');
    $currency = "BDT";
    $success_url = $request->input('callback_url');
    $fail_url = env('APP_URL') . '/payment/fail';
    $cancel_url = env('APP_URL') . '/payment/cancel';
    $ipn_url = env('APP_URL') . '/payment/ipn';

    if (empty($amount) || !is_numeric($amount)) {
        return response()->json(['error' => 'Invalid payment amount.'], 400);
    }

    // Generate a unique transaction ID
    $tran_id = uniqid('ssl_');

    // Append the user_id, plan_id, and transaction_id to the success URL
    $success_url = $success_url . "?user_id=$user_id&plan_id=$plan_id&tran_id=$tran_id&payment_status=success";

    $post_data = [
        'store_id' => "rafusoft0live", 
        'store_passwd' => "679A1049A3D9C60860",
        'total_amount' => $amount,
        'currency' => $currency,
        'tran_id' => $tran_id,
        'success_url' => $success_url,
        'fail_url' => $fail_url,
        'cancel_url' => $cancel_url,
        'ipn_url' => $ipn_url,

        // Required customer details
        'cus_name' => "Test User",
        'cus_email' => "test@example.com",
        'cus_add1' => "Customer Address",
        'cus_city' => "Dhaka",
        'cus_postcode' => "1207",
        'cus_country' => "Bangladesh",
        'cus_phone' => "01700000000",

        // Required shipping details
        'shipping_method' => "NO",
        'num_of_item' => 1,

        // Product details
        'product_name' => "TI SUBSCRIBER",
        'product_category' => "Subscription",
        'product_profile' => "general"
    ];

    // Send request to SSLCommerz API
    $response = Http::asForm()->post('https://securepay.sslcommerz.com/gwprocess/v4/api.php', $post_data);

    if ($response->successful()) {
        $response_data = $response->json();
        if (isset($response_data['GatewayPageURL']) && $response_data['GatewayPageURL'] != '') {
            return redirect()->to($response_data['GatewayPageURL']);
        } else {
            return back()->with('error', 'SSLCOMMERZ payment initialization failed.');
        }
    }

    return back()->with('error', 'Failed to connect to SSLCOMMERZ.');
}


}
