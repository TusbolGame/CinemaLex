<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Mail\ActivityAccount;
use App\Mail\ForgetAccount;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    // Users Status
    // 1000 = payment_step
    // 1001 = payment_cancel
    // 1002 = payment_reactive
    // 1003 = confirm_mail
    // 1004 = active

    /**
     * New Register
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
            'email' => 'required|email|max:70',
            'password' => 'required|confirmed|min:8',
        ]);

        $checkAlreadyUser = User::where('email', $request->input('email'))->first();

        // Check if the email is already
        if (!is_null($checkAlreadyUser)) {
            return response()->json(['status' => 'error', 'message' => 'Email has already been taken.'], 400);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->confirmation_code = str_random(30);
        $user->status = 1000;
        $user->save();

        // Send confirmation message to mail

        return response()->json(['status' => 'success', 'username' => $user->name, 'image' => 'img/avatar.png'], 200);
    }

    /**
     * Send mail confirm
     *
     * @return \Illuminate\Http\Response
     */
    public function sendActivityMail()
    {
        $user = Auth::user();
        if ($user->confirmed !== 1) {
            $user->confirmation_code = str_random(30);
            $user->save();
            Mail::to(Auth::user())
                ->send(new ActivityAccount());
            return response(['status' => 'success', 'message' => 'successful send to '.$user->email ]);
        } else {
            return response()->json(['status' => 404], 404);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $token
     * @return \Illuminate\Http\Response
     */
    public function codeConfirmed($token)
    {
        $user = User::where('confirmation_code', $token)->first();

        if (is_null($user)) {
            return redirect()->route('home');
        }

        $user->confirmation_code = null;
        $user->confirmed = 1;
        $user->save();
        return redirect('/');
    }

    /**
     * Send mail to restore password
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (is_null($user)) {
            return response(['status' => 'error', 'message' => 'The email is incorrect'], 400);
        }

        $user->forget_code = str_random(30);
        $user->save();
        Mail::to($user)
            ->send(new ForgetAccount($user));
        return response(['status' => 'success', 'message' => 'A message has been sent to you by email with instructions on how to reset your password']);
    }

    /**
     * Check the code was sending to email to channe forget password
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function forgetCheckHash(Request $request)
    {
        $request->validate([
            'code' => 'required|max:30|regex:/^[a-z0-9 .\-]+$/i',
        ]);

        // Check if email and code is already
        $hash = User::where('forget_code', $request->input('code'))->first();

        if (!is_null($hash)) {
            return response()->json(['status' => 'success'], 200);
        }
        $user->forget_code = null;
        $user->save();

        abort(404);
    }

    /**
     * Change password after check if the code is correct
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function recoverPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|max:30|regex:/^[a-z0-9 .\-]+$/i',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('forget_code', $request->input('code'))->first();

        if (!is_null($user)) {
            $user->forget_code = null;
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Successful reset your password'], 200);
        } else {

            // if the code is incorrect
            $user->forget_code = null;
            $user->save();
            abort(404);
        }
    }

    /**
     * Make payment after success register
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {
        $request->validate([
            'plan' => 'numeric|min:1|max:4',
        ]);

        $user = Auth::user();
        // Monthly and one weak free;
        // IF you need to add new plan just deplicate esleif and change the request plan id and plan id of stripe.
        // $user->newSubscription('main', 'STRIPE ID')->create($request->token);

        if ($request->input('plan') == 1) {
            $user->newSubscription('main', 'monthly')->create($request->token);
        } elseif ($request->input('plan') == 2) {
            // Yearly
            $user->newSubscription('main', 'yearly')->create($request->token);
        } else {
            return abort(401);
        }
        $details = Auth::user()->subscription('main')->asStripeSubscription();
        $date = date('l jS F Y', strtotime(Carbon::createFromTimestamp($details->trial_end)->toDateTimeString()));
        $user->period_end = date('Y-m-d', strtotime(\Carbon\Carbon::createFromTimestamp($details->current_period_end)->toDateTimeString()));
        $user->save();
        return response()->json(['status' => 'success', 'name' => $user->name, 'email' => $user->email, 'trail_end' => $date, 'card_number' => $user->card_last_four, 'card_brand' => $user->card_brand]);
    }
}
