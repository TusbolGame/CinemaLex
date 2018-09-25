<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tv;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class TvController extends Controller
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
     *  Gel all channels
     *
     * @return []
     */
    public function getAllChannels()
    {
        $getChannels = Tv::get();

        if ($getChannels->isEmpty()) {
            $getChannels = null;
        }

        return response()->json(
            ['status' => 'success',
                'data' => [
                    'channels' => $getChannels,
                    'cdn' => [
                        'cdn_poster' => Storage::disk('s3')->url('posters/'),
                    ],
                ]]
        );
    }

    /**
     * Create new channel
     *
     * @param Request $request
     * @return []
     */

    public function createChannelDetails(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg',
            'name' => 'required|string|max:30',
        ]);

        // Change image size and upload it to S3 Storage
        $logoName = str_random(20) . '.jpg';
        Storage::disk('s3')->put('posters/' . $logoName, file_get_contents($request->file('image')));
        $store = new Tv();
        $store->name = $request->name;
        $store->image = $logoName;
        $store->save();
        return response()->json(['status' => 'success', 'message' => 'Successful store channel details in database', 'id' => $store->id], 200);
    }

    /**
     * Upload video or store exist link
     *
     * @param Request $request
     * @return void
     */
    public function uploadChannelVideo(Request $request)
    {
        if (!empty($request->exit_link) && $request->exit_link !== 'undefined') {
            $request->validate([
                'exit_link' => 'nullable|url'
            ]);

            //Store video data
            $store = Tv::find($request->id);
            if (is_null($store)) {
                abort(404);
            }
            $store->type = 'hls_exit';
            $store->link = $request->exit_link;
            $store->save();
            return response()->json(['status' => 'success', 'message' => 'Successful insert the link m3u8', 'id' => $request->id], 200);
        } else {
            $request->validate([
                'video' => 'nullable|mimes:video/x-flv,video/mp4,application/x-mpegURL',
            ]);
            // Upload live file and convert it
            $hash_name = str_random(20);
            $request->file('video')->storeAs('tv/' . $request->name . '/', $request->file('video')->getClientOriginalName(), 's3');

            if ($request->hls === 'hls_2m') {
                $resolution = [
                    '1' => [
                        'resolution' => '1351620000001-200010',
                        'name' => str_random(20) . '.m3u8',
                        'type' => 'HLS 2M',
                        'check' => true,
                    ],
                ];
            } elseif ($request->hls === 'hls_1_5m') {
                $resolution = [
                    '2' => [
                        'resolution' => '1351620000001-200020',
                        'name' => str_random(20) . '.m3u8',
                        'type' => 'HLS 1.5M',
                        'check' => true,

                    ],
                ];
            } elseif ($request->hls === 'hls_1m') {
                $resolution = [
                    '3' => [
                        'resolution' => '1351620000001-200030',
                        'name' => str_random(20) . '.m3u8',
                        'type' => 'HLS 1M',
                        'check' => true,

                    ],
                ];
            } elseif ($request->hls === 'hls_600k') {
                $resolution = [
                    '4' => [
                        'resolution' => '1351620000001-200040',
                        'name' => str_random(20) . '.m3u8',
                        'type' => 'HLS 600k',
                        'check' => true,
                    ]];
            } else {
                $resolution = [
                    '5' => [
                        'resolution' => '1351620000001-200050',
                        'name' => str_random(20) . '.m3u8',
                        'type' => 'HLS 400k',
                        'check' => true,
                    ],
                ];
            }
            foreach ($resolution as $res => $index) {
                if ($index['check'] === true) {
                    $ElasticTranscoder = App::make('aws')->createClient('ElasticTranscoder');
                    $ElasticTranscoder->createJob([
                        //pipeline id refer it in transcoder page eg : https://ap-southeast-2.console.aws.amazon.com/elastictranscoder/home?region=ap-southeast-2#pipelines:
                        'PipelineId' => config('app.pipeline'),
                        'Input' => [
                            //input file, which should exist in the bucket which is specified on pipeline
                            'Key' => 'tv/' . $request->name . '/' . $request->file('video')->getClientOriginalName(),
                            'SegmentDuration' => 10,
                        ],
                        'Outputs' => [
                            [
                                //output prefix file name
                                'Key' => $index['name'],
                                //System preset: HLS Audio - 64k

                                //Preset id refer it in transcoder page eg : https://ap-southeast-2.console.aws.amazon.com/elastictranscoder/home?region=ap-southeast-2#presets:

                                'PresetId' => $index['resolution'],
                                //seconds to split to file
                            ],
                        ],
                        //output folder name
                        'OutputKeyPrefix' => 'tv/' . $request->name . '/',
                    ]);

                    //Store video data
                    $store = new Tv();
                    $store->name = $request->name;
                    $store->image = $name_poster;
                    $store->type = $index['type'];
                    $store->link = $index['name'];
                    $store->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to s3', 'id' => $request->id], 200);
    }

    /**
     * Delete channgel
     *
     * @param [uuid] $id
     * @return []
     */
    public function deleteChannel($id)
    {
        $delete = Tv::find($id);
        if (is_null($delete)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id'], 422);
        }

        $delete->delete();
        return response()->json(['status' => 'success', 'message' => 'Successful delete']);
    }
}
