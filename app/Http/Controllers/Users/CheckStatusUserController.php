<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Helpers;

class CheckStatusUserController extends Controller
{
    public function checkUserStatus()
    {
        $user = Auth::user();

        // if has StripeId

        if ($user->hasStripeId()) {
            if ($user->period_end >= date("Y-m-d")) {
                $cancel_time = $user->subscription('main')->ends_at;
                if ($cancel_time !== null) {
                    $user->status = 1001;
                    $user->period_end = $cancel_time;
                    $user->save();
                    return response()->json([
                    'name' => $user->name,
                    'image' =>  Helpers::getAwsUrl('user').$user->image,
                    'email' => $user->email,
                    'language' => $user->language,
                    'status' => 'cancel',
                    'cancel_time' =>  $user->subscription('main')->ends_at,
                    'caption' => $user->caption
                    ]);
                } else {

                   // When the mail is not confirmed
                    if ($user->confirmed === 0) {
                        return response()->json([
                        'name' => Auth::user()->name,
                        'image' => Helpers::getAwsUrl('user').$user->image,
                        'email' => Auth::user()->email,
                        'status' => 'confirm_step',
                        'language' => $user->language,
                        'caption' => $user->caption]);
                    } else {
                        return response()->json([
                        'name' => $user->name,
                        'image' =>  Helpers::getAwsUrl('user').$user->image,
                        'email' => $user->email,
                        'status' => 'active',
                        'language' => $user->language,
                        'caption' => $user->caption]);
                    }
                }
            } else {
                // Get subscription details

                $payment_details = Auth::user()->subscription('main')->asStripeSubscription();
                $period_end_date = date('Y-m-d', strtotime(\Carbon\Carbon::createFromTimestamp($payment_details->current_period_end)->toDateTimeString()));
                $detect_status = $payment_details->status;


                //When an invoice payment on a subscription fails, the subscription becomes past_due.
                //After Stripe exhausts all payment retry attempts, the subscription remains past_due or moves to a status of either canceled or unpaid, depending upon your retry settings.
                //https://stripe.com/docs/subscriptions/lifecycle

                if ($detect_status === 'past_due' || $detect_status === 'canceled' || $detect_status === 'unpaid') {
                    $user->status = 1002;
                    $user->save();
                    if ($payment_details->plan->id === 'monthly') {
                        $plan = 1;
                    } else {
                        $plan = 2;
                    }
                    return response()->json([
                        'plan' => $plan,
                        'email' => $user->email,
                        'name' => $user->name,
                        'status' => 'payment_reactive',
                        'language' => 'en',
                       ]);
                }

                // When subscription is active

                if ($detect_status === 'active'|| $detect_status === 'trialing' && $period_end_date >= date("Y-m-d")) {
                    $user->period_end = $period_end_date;
                    $user->status = 1004;
                    $user->save();
                    return response()->json([
                        'name' => $user->name,
                        'image' =>  Helpers::getAwsUrl('user').$user->image,
                        'email' => $user->email,
                        'status' => 'active',
                        'language' => $user->language,
                        'caption' => $user->caption]);
                }
            }
        } else {
            return response()->json([
            'name'  => Auth::user()->name,
            'image' =>  Helpers::getAwsUrl('user').Auth::user()->image,
            'email' => Auth::user()->email,
            'status' => 'payment_step',
            'language' => 'en',], 200);
        }
    }
}
