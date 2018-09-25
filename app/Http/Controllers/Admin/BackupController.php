<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Backup;
use Auth;

class BackupController extends Controller
{
    /**
     * Only admin can access
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin']);
            return $next($request);
        });
    }


    /**
     *  Get all backup object from s3
     *
     * @return void
     */
    public function getAll()
    {
        $url = substr(Storage::disk('s3')->url('backup'), 0, -6);
        $get = Storage::disk('s3')->files('backup');
        if (empty($get)) {
            return response()->json(['status' => 'empty']);
        }
        return response()->json(['status' => 'success',$get,'url' => $url]);
    }


    /**
     * Create new backup
     *
     * @param Request $request
     * @return void
     */
    public function newBackup(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric'
        ]);
        if ($request->input('id') === 0) {
            \Artisan::call('backup:run', ['--only-files' => '']);
        } elseif ($request->input('id') === 1) {
            \Artisan::call('backup:run', ['--only-db' => '']);
        }
        return response()->json(['status' => 'success']);
    }

    /**
     * Download backup
     *
     * @param Request $request
     * @return void
     */
    public function downloadBackup(Request $request)
    {
        $assetPath = Storage::disk('s3')->url($request->input('name'));
        return \Redirect::to($assetPath);
    }


    /**
     *  Delete backup
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        $assetPath = Storage::disk('s3')->delete($request->input('name'));
        return response()->json(['status' => 'success']);
    }
}
