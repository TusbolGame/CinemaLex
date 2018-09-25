<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin', 'manager']);
            return $next($request);
        });
    }

    /**
     * Get all users
     *
     * @return void
     */
    public function getAllUsers()
    {
        $getAllUsers = DB::table('users')
            ->selectRaw('id,name,email,image,created_at,confirmed,period_end,created_at,updated_at')
            ->paginate(15);

        // Check if empty result
        if (empty($getAllUsers->all())) {
            $getAllUsers = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $getAllUsers,
                'cdn' => [
                    'cdn_user' => Storage::disk('s3')->url('users_image/'),
                ],
            ],
        ]);
    }

    /**
     * Get inactivity users
     *
     * @return void
     */

    public function getInactivityUsers()
    {
        $getInactivityUsers = DB::table('users')
            ->selectRaw('id,name,email,image,created_at,confirmed,period_end,created_at,updated_at')
            ->whereRaw('period_end < NOW() ')
            ->paginate(15);

        // Check if empty result
        if (empty($getInactivityUsers->all())) {
            $getInactivityUsers = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $getInactivityUsers,
                'cdn' => [
                    'cdn_user' => Storage::disk('s3')->url('users_image/'),
                ],
            ],
        ]);
    }

    /**
     * Get blocked users
     *
     * @return void
     */

    public function getActivityUsers()
    {
        $getActitivyUsers = DB::table('users')
            ->selectRaw('id,name,email,image,created_at,confirmed,period_end,created_at,updated_at')
            ->whereRaw('period_end > NOW() ')
            ->paginate(15);

        // Check if empty result
        if (empty($getActitivyUsers->all())) {
            $getActitivyUsers = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $getActitivyUsers,
                'cdn' => [
                    'cdn_user' => Storage::disk('s3')->url('users_image/'),
                ],
            ],
        ]);
    }

    public function delete($id)
    {
        $getUser = User::find($id);

        if (is_null($getUser)) {
            return response()->json(['status' => 'failed', 'message' => 'Not found any user'], 422);
        }

        $getUser->delete();
        return response()->json(['status' => 'success', 'message' => 'Successful delete ' . $getUser->name]);
    }

    public function getUserDetails($id)
    {
        $getUser = User::find($id);

        if (is_null($getUser)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id'], 422);
        }

        return response()->json(
            ['status' => 'success',
             'data' => [
                 'id' => $getUser->id,
                 'name' => $getUser->name,
                 'email' => $getUser->email,
                 'stripe_id' => $getUser->stripe_id,
                 'card_brand' => $getUser->card_brand,
                 'card_last_four' => $getUser->card_last_four,
                 'language' => $getUser->language,
                 'status' => $getUser->status,
                 'created_at' => $getUser->created_at,
                 'updated_at' => $getUser->updated_at,
             ]]
        );
    }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'name' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
            'email' => 'required|email|max:150',
            'stripe_id' => 'required|max:20',
        ]);

        $getUser = User::find($request->input('id'));

        if (is_null($getUser)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id'], 422);
        }

        $getUser->name = $request->input('name');
        $checkAlreadyEmail = User::where('email', $request->email)->where('id', '<>', $request->input('id'))->first();
        
        if (! is_null($checkAlreadyEmail)) {
            return response()->json(['status' => 'failed', 'message' => 'The email already exists'], 422);
        } else {
            $getUser->email = $request->input('email');
        }

        if ($request->input('password') !== null) {
            $getUser->password = Hash::make($request->input('password'));
        }
        $getUser->save();
        return response()->json(['status' => 'success', 'message' => 'Successful update '. $getUser->name]);
    }

    public function getUsersBySearch(Request $request)
    {
        $request->validate([
            'query' => 'nullable|max:50',
        ]);

        if (empty($request->input('query'))) {
            $getUsers = null;
        } else {
            $getUsers = DB::table('users')
                ->selectRaw('id,name,email,image,created_at,confirmed,period_end,created_at,updated_at')
                ->where('email', 'like', $request->input('query') . '%')
                ->orWhere('name', 'like', $request->input('query') . '%')
                ->limit(10)
                ->get();

            // Check if empty result
            if ($getUsers->isEmpty()) {
                $getUsers = null;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $getUsers,
                'cdn' => [
                    'cdn_user' => Storage::disk('s3')->url('users_image/'),
                ],
            ],
        ]);
    }

    public function getUserInvoice(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
        ]);

        $user = User::find($request->id);
        if ($user->hasStripeId()) {
            $invoices = $user->invoices()->map(function ($invoice) {
                return [
                    'start' => date('l jS F Y', strtotime(Carbon::createFromTimestamp($invoice->lines->data[0]->period->start)->toDateTimeString())),
                    'end' => date('l jS F Y', strtotime(Carbon::createFromTimestamp($invoice->lines->data[0]->period->end)->toDateTimeString())),
                    'total' => $invoice->total,
                ];
            });
            $subscription = $user->subscription('main')->asStripeSubscription();
        } else {
            $invoices = [];
        }

        return response()->json([
            'status' => 'success',
            'data' => ['invoices' => $invoices,
                'name' => $subscription->plan->name,
                'amount' => $subscription->plan->amount,
            ]]);
    }
}
