<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Helpers;
use App\User;
use DB;
use Validator;

class ProfileController extends Controller
{

    /**
     * Upload new image
     *
     * @param Request $request
     * @return void
     */
    public function avatarUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png|max:1000',
        ]);
        // upload to s3 storage
        $image = \Image::make($request->image)->widen(200)->encode('jpg');
        $name  = str_random().'.jpg';
        $upload = Storage::disk('s3')->put('users_image/' .$name, $image->__toString());
        if ($upload) {
            $user = Auth::user();
            $user->image = $name;
            $user->save();
            return response()->json(['status' => 'success','cdn_user' => Helpers::getAwsUrl('user'), 'image' => $user->image]);
        }
    }


    /**
     * Update name and email
     *
     * @param Request $request
     * @return void
     */
    public function updateDetails(Request $request)
    {
        $request->validate([
            'current_password' => 'required|min:8',
            'name' => 'required|max:100',
            'email' => 'required|email|max:150',
        ]);

        $check = User::where('email', $request->email)->first();

        if (is_null($check)) {
            return response()->json(['status' => 'failed', 'message' => 'Email has already been taken'], 422);
        }
    
        $user = Auth::user();

        if (Hash::check($request->input('current_password'), $user->password)) {
            $user->name  = $request->name;
            $user->email  = $request->email;
            $user->save();
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Your password was incorrect'], 422);
        }
        return response()->json(['status' => 'success', 'message' => 'Successful update']);
    }

    /**
     * Update password
     *
     * @param Request $request
     * @return void
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'current_password' => 'required|min:8',

        ]);
        $user = Auth::user();
        
        if (Hash::check($request->input('current_password'), $user->password)) {
            $user->password  = Hash::make($request->password);
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Successful update password']);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Your password was incorrect'], 422);
        }
    }

    /**
     * Get payment info
     *
     * @return void
     */
    public function getPaymentInfo()
    {
        $user = Auth::user();
        $subscription =  $user->subscription('main')->asStripeSubscription();
        return response()->json(['status' => 'success' ,'subscription_plan' => $subscription->plan->id,'subscription_status' => $subscription->status, 'cancel' => $subscription->cancel_at_period_end , 'card_number' => $user->card_last_four,'card_brand' => $user->card_brand]);
    }


    /**
     * Update payment
     *
     * @param Request $request
     * @return void
     */
    public function paymentUpdate(Request $request)
    {
        $user = Auth::user();
        $user->updateCard($request->token);
        return response()->json(['status' => 'success', 'message' => 'Successful update']);
    }

    /**
     * Get invoices
     *
     * @return void
     */
    public function getBillingDetails()
    {
        $user =  Auth::user();
        if ($user->hasStripeId()) {
            $invoices = $user->invoices()->map(function ($invoice) {
                return [
                    'start'  =>  date('l jS F Y', strtotime(Carbon::createFromTimestamp($invoice->lines->data[0]->period->start)->toDateTimeString())),
                    'end'    =>  date('l jS F Y', strtotime(Carbon::createFromTimestamp($invoice->lines->data[0]->period->end)->toDateTimeString())),
                    'total'  => $invoice->total
                  ];
            });
        } else {
            $invoices = [];
        }
        $subscription =  Auth::user()->subscription('main')->asStripeSubscription();
        
        return response()->json([ 'invoices' => $invoices,'name' => $subscription->plan->name,'amount' => $subscription->plan->amount, ]);
    }

    /**
     * Cancel memebership
     *
     * @return boolean
     */
    public function cancelMembership()
    {
        $user = Auth::user();
        $user->subscription('main')->cancel();
        $subscription =  $user->subscription('main')->asStripeSubscription();
        return response()->json(['status' => 'success' ,'subscription_status' => $subscription->status, 'cancel' => $subscription->cancel_at_period_end]);
    }
    
 
    /**
     * Resume memebership
     *
     * @return void
     */
    public function resumeMembership()
    {
        $user = Auth::user();
        $user->subscription('main')->resume();
        $subscription =  $user->subscription('main')->asStripeSubscription();
        return response()->json(['status' => 'success' ,'subscription_status' => $subscription->status, 'cancel' => $subscription->cancel_at_period_end]);
    }
    
    /**
     * Change plan
     *
     * @param Request $request
     * @return void
     */
    public function changePlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|numeric|max:2'
        ]);

        // Just add esleif statment and the request plan_id === the id number
        // $request->plan_id = 'Stripe id';
        if ($request->plan_id === 1) {
            $request->plan_id = 'monthly';
        } else {
            $request->plan_id = 'yearly';
        }
        $user = Auth::user();
        $user->subscription('main')
        ->skipTrial()
        ->swap($request->plan_id);
     
        return response()->json(['status' => 'success', 'message' => 'Successful Update']);
    }

    /**
     * Change language
     *
     * @param Request $request
     * @return void
     */
    public function changeLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|min:1|max:4',
        ]);
        $user = Auth::user();
        $user->language = $request->language;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Successful Update']);
    }


    /**
     *  Change caption
     *
     * @param Request $request
     * @return void
     */
    public function changeCaption(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'caption' => [
                'required',
                "regex:^(auto|0)$|^[+-]?[0-9]+.?([0-9]+)?(px|em|ex|%|in|cm|mm|pt|pc)$"
            ]
        ]);
        $caption = json_encode($request->caption);
        $user = Auth::user();
        $user->caption = $caption;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Successful Update']);
    }

    /**
     * Get viewing history
     *
     * @return void
     */
    public function getViewingHistory()
    {
        $getViewingHistoryMovie = DB::table('recently_watcheds')
        ->selectRaw('"movie" AS type ,movies.m_name AS name, movies.m_id AS id, null AS episode_number, recently_watcheds.created_at')
        ->join('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
        ->where('recently_watcheds.uid', Auth::id())
        ->limit(30);

        $getViewingHistorySeries = DB::table('recently_watcheds')
        ->selectRaw('"series" AS type ,series.t_name AS name, series.t_id AS id,episodes.episode_number, recently_watcheds.created_at')
        ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
        ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
        ->where('recently_watcheds.uid', Auth::id())
        ->union($getViewingHistoryMovie)
        ->limit(30)
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $getViewingHistorySeries
        ]);
    }
}
