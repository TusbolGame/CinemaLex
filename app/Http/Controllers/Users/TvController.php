<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers;
use App\Tv;

class TvController extends Controller
{

    /**
     * Get all channels
     *
     * @return []
     */
    public function getAll()
    {
        $getChannels = Tv::get();
   
        return response()->json(
            ['data' => [
                'channels' => $getChannels,
                'cdn' => [
                    'cdn_poster' => Helpers::getAwsUrl('poster'),
                ]
            ] ]
   
        );
    }

    /**
     * Get some channel info
     *
     * @param [uuid] $id
     * @return []
     */
    public function getChannels($id)
    {
        $get = Tv::find($id);

        if (is_null($get)) {
            return response()->json(['status' => 404], 404);
        }

        if ($get->type ==='hls_exit') {
            return response()->json(['link' => $get->link, $get]);
        } else {
            return response()->json(['link' => Helpers::getAwsUrl('tv').$get->name.'/'.$get->link, $get]);
        }
    }
}
