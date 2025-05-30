<?php
namespace App\Http\Controllers\api\Frontend;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    public function bookingIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name'   => 'required|string',
            'amount'         => 'required',
            'payment_method' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $paymentIntent = PaymentIntent::create([
                'amount'         => $request->amount * 100,
                'currency'       => 'usd',
                'payment_method' => $request->payment_method,
                'metadata'       => [
                    'service_name' => $request->service_name,
                ],
            ]);
            return response()->json([
                'data' => $paymentIntent,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
