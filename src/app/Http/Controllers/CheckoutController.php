<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // Stripe APIキーの設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            Charge::create([
                'amount' => 1000,
                'currency' => 'jpy',
                'source' => $request->stripeToken,
                'description' => 'Shop Reservation Payment',
            ]);
            return back()->with('success', '決済が完了しました！');
        } catch (\Exception $e) {
            return back()->with('error', '決済に失敗しました: ' . $e->getMessage());
        }
    }
}
