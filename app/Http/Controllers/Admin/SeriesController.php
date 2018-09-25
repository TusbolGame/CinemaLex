<?php

namespace App\Http\Controllers\Admin;

use App\Casts;
use App\Casts_rules;
use App\Episode;
use App\Http\Controllers\Controller;
use App\Series;
use App\Subtitle;
use App\Video;
use Auth;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \Done\Subtitles\Subtitles;

class SeriesController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSeries()
    {
        $getSeries = Series::selectRaw('
                        series.t_id AS id,
                        series.t_name AS name,
                        series.t_poster AS poster,
                        series.t_year AS year,
                        series.created_at,
                        series.updated_at,
                        tops.series_id')
            ->leftJoin('tops', 'tops.series_id', '=', 'series.t_id')
            ->paginate(15);

        // Check if there is no result
        if (empty($getSeries->all())) {
            $getSeries = null;
        }
        return response()->json(
            ['data' => [
                'series' => $getSeries,
                'cdn' => [
                    'cdn_poster' => Storage::disk('s3')->url('posters/'),
                ]]],
            200
        );
    }

    public function getSeriesByApi(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);

        // Check if there is api of moviedb in config file
        if (empty(config('app.api'))) {
            return response()->json(['status' => 'failed', 'message' => 'There is no api key in config'], 422);
        }

        $token = config('app.api');
        $client = new \GuzzleHttp\Client();

        // Get All Series Details From Themoviedb Api
        $details = 'https://api.themoviedb.org/3/tv/' . $request->input('id') . '?api_key=' . $token . '&language=en-US';

        try {
            $res_series = $client->get($details)->getBody();
            $data_series = json_decode($res_series, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        // Get the backdrop and poster image from api themoviedb and
        $name_poster = (!isset($data_series['poster_path']) ? 'none' : str_random(20) . '.jpg');
        $poster = (!isset($data_series['poster_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w500/' . $data_series['poster_path'])->getBody());

        $name_backdrop = (!isset($data_series['backdrop_path']) ? 'none' : str_random(20) . '.jpg');
        $backdrop = (!isset($data_series['backdrop_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w1280/' . $data_series['backdrop_path'])->getBody());

        // If there no movies image @return json "Error"
        if ($name_poster === 'none' || $name_backdrop === 'none') {
            return response()->json(['status' => 'failed', 'message' => 'Please use custom upload, because there is no poster or backdrop for this series'], 422);
        } else {
            // Change image size and upload it to S3 Storage
            $img1 = \Image::make($poster)->encode('jpg');
            $img2 = \Image::make($backdrop)->encode('jpg');
            Storage::disk('s3')->put('posters/' . $name_poster, $img1->__toString());
            Storage::disk('s3')->put('backdrops/' . $name_backdrop, $img2->__toString());
        }

        // Store data
        $store = new Series;
        $store->t_moviedbid = $request->input('id');
        $store->t_age = $request->input('age');
        $store->t_name = $data_series['original_name'];
        $store->t_desc = $data_series['overview'];
        $store->t_year = substr($data_series['first_air_date'], 0, 4);
        $store->t_rate = $data_series['vote_average'];
        $store->t_backdrop = $name_backdrop;
        $store->t_poster = $name_poster;
        foreach ($data_series['genres'] as $key => $value) {
            $genre1[] = $value['name'];
            $genre2 = implode(", ",$genre1);
        }
        $store->t_genre = $genre2;
        $store->save();

        // Get the casts details from TheMovieDB and store it in database
        $casts = 'https://api.themoviedb.org/3/tv/' . $request->input('id') . '/credits?api_key=' . $token . '&language=en-US';

        // Check if there cast or not
        try {
            $res_casts = $client->get($casts)->getBody();
            $data_casts = json_decode($res_casts, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no cast'], 422);
        }

        foreach ($data_casts['cast'] as $key => $value) {
            // Break if more than 8 cast
            if ($key > 7) {
                break;
            }

            //Check if image not empty
            if (!empty($value['profile_path'] && !empty($value['credit_id']))) {
                $image_name = str_random(20) . '.jpg';
                $image_content = $client->get('http://image.tmdb.org/t/p/w500/' . $value['profile_path'])->getBody();
                $image_encode = \Image::make($image_content)->widen(200)->encode('jpg');
                Storage::disk('s3')->put('casts/' . $store->t_id . '/' . $image_name, $image_encode->__toString());

                //If there same cast in db
                $check_cast = Casts::where('credit_id', $value['credit_id'])->first();
                if (!is_null($check_cast)) {
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_series = $store->t_id;
                    $casts_rule->save();
                } else {
                    $casts_store = new Casts;
                    $casts_store->credit_id = $value['credit_id'];
                    $casts_store->c_name = $value['name'];
                    $casts_store->c_image = $store->t_id . '/' . $image_name;
                    $casts_store->save();
                    //Casts rules
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_series = $store->t_id;
                    $casts_rule->save();
                }
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Successful store series details in database', 'id' => $store->t_id], 200);
    }

    public function customUploadSeriesDetails(Request $request)
    {
        //Validate
        $this->validate($request, [
            'name' => 'required|max:50|regex:/^[a-z0-9 .\-]+$/i',
            'overview' => 'required|max:500',
            'year' => 'required|numeric|between:0,2030',
            'rate' => 'required|numeric|between:0,99.99',
            'genres' => 'required|max:100',
            'poster' => 'required|mimes:jpeg,jpg,png',
            'backdrop' => 'required|mimes:jpeg,jpg,png',
        ]);

        // Upload images to s3
        $posterName = md5($request->file('poster')->getClientOriginalName() . microtime()) . '.jpg';
        $backdropName = md5($request->file('backdrop')->getClientOriginalName() . microtime()) . '.jpg';
        $posterImage = \Image::make($request->poster)->encode('jpg');
        $backdropImage = \Image::make($request->backdrop)->encode('jpg');
        Storage::disk('s3')->put('posters/' . $posterName, $posterImage->__toString());
        Storage::disk('s3')->put('backdrops/' . $backdropName, $backdropImage->__toString());

        //Store data
        $store = new Series;
        $store->t_name = $request->name;
        $store->t_desc = $request->overview;
        $store->t_year = $request->year;
        $store->t_rate = $request->rate;
        $store->t_backdrop = $backdropName;
        $store->t_poster = $posterName;
        $store->t_age = $request->age;
        $store->t_genre = str_replace(",", "-", $request->genres);
        $store->save();

        $casts = [
            '1' => [
                'id' => str_random(20),
                'name' => $request->cast1,
                'image' => $request->image1,
            ],
            '2' => [
                'id' => str_random(20),
                'name' => $request->cast2,
                'image' => $request->image2,
            ],
            '3' => [
                'id' => str_random(20),
                'name' => $request->cast3,
                'image' => $request->image3,
            ],
            '4' => [
                'id' => str_random(20),
                'name' => $request->cast4,
                'image' => $request->image4,
            ],
        ];

        foreach ($casts as $key => $value) {
            $img_name = str_random(20) . '.jpg';
            $img = \Image::make($value['image'])->widen(200)->encode('jpg');
            Storage::disk('s3')->put('casts/' . $store->t_id . '/' . $img_name, $img->__toString());
            $casts_store = new Casts();
            $casts_store->credit_id = $value['id'];
            $casts_store->c_name = $value['name'];
            $casts_store->c_image = $store->t_id . '/' . $img_name;
            $casts_store->save();

            $casts_rule = new Casts_rules();
            $casts_rule->casts_id = $value['id'];
            $casts_rule->casts_movies = $store->t_id;
            $casts_rule->save();
        }

        return response()->json(['status' => 'success', 'msg' => 'Successful upload ' . $request->name]);
    }

    /**
     * Get episode details form TheMovieDB
     *
     * @param Request $request
     * @return void
     */
    public function getEpisodeDetailsApi(Request $request)
    {
        $request->validate([
            'series_id' => 'required|uuid',
            'season_number' => 'required|numeric|max:1000',
            'episode_number' => 'required|numeric|max:1000',
        ]);

        // Check if series is exist already
        $checkAlreadySeries = Series::where('t_id', $request->input('series_id'))->first();

        // Throw error if there is no series equal id in database
        if (is_null($checkAlreadySeries)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        // Get api from config file
        $token = config('app.api');
        $client = new \GuzzleHttp\Client();

        // Get all episode details from themoviedb Api
        // https://developers.themoviedb.org/3/tv-episodes/get-tv-episode-details

        $movidedbLink = 'https://api.themoviedb.org/3/tv/' . $checkAlreadySeries->t_moviedbid . '/season/' . $request->input('season_number') . '/episode/' . $request->input('episode_number') . '?api_key=' . $token . '&language=en-US';

        try {
            $getEpisodeData = $client->get($movidedbLink)->getBody();
            $episodeData = json_decode($getEpisodeData, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no series like this id.'], 422);
        }

        // Check if there is episode image or not
        if (!isset($episodeData['still_path'])) {
            return response()->json(['status' => 'error', 'message' => 'There is no episode, upload it from custom upload'], 422);
        }

        // Get image and uplaod it to s3
        // The size of episode backdrop is 1000, check more size from
        // https://developers.themoviedb.org/3/getting-started/images

        $backdrop = $client->get('https://image.tmdb.org/t/p/w1000/' . $episodeData['still_path'])->getBody();
        $backdropName = str_random(20) . '.jpg';
        $backdropEncode = \Image::make($backdrop)->encode('jpg');
        Storage::disk('s3')->put('backdrops/' . $backdropName, $backdropEncode->__toString());

        //Store Episode Data
        $store = new Episode;
        $store->series_id = $request->input('series_id');
        $store->season_number  = $request->input('season_number');
        $store->episode_number = $request->input('episode_number');
        $store->name = $episodeData['name'];
        $store->overview = $episodeData['overview'];
        $store->backdrop = $backdropName;
        $store->save();
        return response()->json(['status' => 'success', 'message' => 'Successful store series details in database', 'id' => $store->id], 200);
    }


    /**
     * Upload video to s3 and transcode it
     *
     * @param Request $request
     * @return void
     */
    public function UploadVideoToS3(Request $request)
    {
        // if there is video_link add it and save the resolution as 1024p
        if ($request->video_link !== 'undefined' && !empty($request->video_link)) {
            $video = new video();
            $video->resolution = '720';
            $video->episode_id = $request->id;
            $video->video_url = $request->video_link;
            $video->type = 'link';
            $video->save();
            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to s3', 'id' => $request->id], 200);
        } elseif ($request->video !== 'undefined') {
            // Upload video to S3 Storage
            $hash_name = str_random(20);
            $extension = $request->file('video')->guessExtension();
            $request->file('video')->storeAs('videos/' . $request->id . '/', $hash_name . '.' . $extension, 's3');

            // Using (elastictranscoder) to change resolution of video
            if ($request->resolution_1080 === 'true') {
                $video = new Video();
                $video->resolution = '1440';
                $video->episode_id = $request->id;
                $video->type = 's3';
                $video->video_url = $request->id . '/' . $hash_name . '.' . $extension;
                $video->save();
            } elseif ($request->resolution_720 === 'true') {
                $video = new Video();
                $video->resolution = '1080';
                $video->episode_id = $request->id;
                $video->type = 's3';
                $video->video_url = $request->id . '/' . $hash_name . '.' . $extension;
                $video->save();
            } else {
                $video = new Video();
                $video->resolution = '720';
                $video->episode_id = $request->id;
                $video->type = 's3';
                $video->video_url = $request->id . '/' . $hash_name . '.' . $extension;
                $video->save();
            }
            $resolution = [
                '1' => [
                    'resolution' => '1351620000001-000040', // 360
                    'name' => str_random(20) . '.mp4',
                    'type' => '360',
                    'check' => $request->resolution_360,
                ],
                '2' => [
                    'resolution' => '1351620000001-000020', // 480
                    'name' => str_random(20) . '.mp4',
                    'type' => '480',
                    'check' => $request->resolution_480,

                ],
                '3' => [
                    'resolution' => '1351620000001-000010', // 720
                    'name' => str_random(20) . '.mp4',
                    'type' => '720',
                    'check' => $request->resolution_720,

                ],
                '4' => [
                    'resolution' => '1351620000001-000001', // 1080
                    'name' => str_random(20) . '.mp4',
                    'type' => '1080',
                    'check' => $request->resolution_1080,
                ],
            ];
            foreach ($resolution as $key => $value) {
                if ($value['check'] === 'true') {
                    $ElasticTranscoder = App::make('aws')->createClient('ElasticTranscoder');
                    $ElasticTranscoder->createJob([
                        //pipeline id refer it in transcoder page eg : https://ap-southeast-2.console.aws.amazon.com/elastictranscoder/home?region=ap-southeast-2#pipelines:
                        'PipelineId' => config('app.pipeline'),
                        'Input' => [
                            //input file, which should exist in the bucket which is specified on pipeline
                            'Key' => 'videos/' . $request->id . '/' . $hash_name . '.' . $extension,
                            'FrameRate' => 'auto',
                            'Resolution' => 'auto',
                            'AspectRatio' => 'auto',
                            'Interlaced' => 'auto',
                            'Container' => 'auto',
                        ],
                        'Outputs' => [
                            [
                                //output prefix file name
                                'Key' => $value['name'],

                                //Preset id refer it in transcoder page eg : https://ap-southeast-2.console.aws.amazon.com/elastictranscoder/home?region=ap-southeast-2#presets:
                                'PresetId' => $value['resolution'],
                                //seconds to split to file
                            ],
                        ],
                        //output folder name
                        'OutputKeyPrefix' => 'videos/' . $request->id . '/',
                    ]);

                    //Store video data
                    $video = new Video();
                    $video->resolution = $value['type'];
                    $video->episode_id = $request->id;
                    $video->video_url = $request->id . '/' . $value['name'];
                    $video->type = 's3';
                    $video->save();
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Successful upload and transcode video to s3', 'id' => $request->id], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'There is something error with video'], 422);
        }
    }

    /**
     * Convert subtitle srt format to vtt and upload it to s3
     *
     * @param Request $request
     * @return void
     */
    public function UploadSubtitleToS3(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
        ]);

        if (! empty($request->file('subtitleUpload'))) {
            foreach ($request->file('subtitleUpload') as $key => $value) {
                $file = file_get_contents($value);
                $subtitles = Subtitles::load($file, 'srt');
                $name = uniqid('subtitle_') . '.vtt';
                Storage::disk('s3')->put('subtitles/' . $request->id . '/' . $name, $subtitles->content('vtt'));
                // Store Data
                $sub = new Subtitle();
                $sub->name = $request->id . '/' . $name;
                $sub->language = substr($value->getClientOriginalName(), 0, -4);
                $sub->episode_id = $request->id;
                $sub->save();
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Successful upload subtitles', 'data' => $sub]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getAllSeason($id)
    {
        $series = Series::find($id);

        // Check if series is already in database
        if (is_null($series)) {
            return response()->json(['status' => 'success', 'message' => 'There is no series found'], 404);
        }

        // Get season
        $getSeason = DB::table('episodes')
            ->join('series', 'series.t_id', '=', 'episodes.series_id')
            ->where('series.t_id', $id)
            ->paginate(15);

        // Check if there is no result
        if (empty($getSeason->all())) {
            $getSeason = null;
        }
        return response()->json([
            'status' => 'success',
            'data' => [
                'season' => $getSeason,
                'cdn' => [
                    'cdn_poster' => Storage::disk('s3')->url('posters/'),
                ],
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getSeries($id)
    {
        $getSeries = DB::table('series')
                    ->selectRaw('series.t_id AS id, series.t_name AS name, series.t_poster AS poster, series.t_desc AS overview,  series.t_year AS year, series.t_genre AS genre, series.t_rate AS rate, series.t_backdrop AS backdrop, series.t_age AS age')
                    ->where('t_id', $id)
                    ->first();

        if (is_null($getSeries)) {
            abort(404);
        }
        
        $getAllCasts = Casts::select('credit_id AS new_credit_id', 'c_image AS image', 'c_name AS name')->orderBy('c_name', 'ASC')->get();
        $getCastOfSeries = DB::table(DB::raw('casts'))
            ->select('casts.credit_id', 'casts.c_name AS name', 'casts.c_image AS image')
            ->join('casts_rules', 'casts_rules.casts_id', '=', 'casts.credit_id')
            ->where('casts_rules.casts_series', $id)
            ->orderBy('c_name', 'ASC')
            ->get();

        return response()->json([
            'data' => [
                'series' => $getSeries,
                'cast' => $getCastOfSeries,
                'all_cast' => $getAllCasts,
                'cdn' => [
                    'cdn_poster' => Storage::disk('s3')->url('posters/'),
                    'cdn_backdrop' => Storage::disk('s3')->url('backdrops/'),
                    'cdn_cast' => Storage::disk('s3')->url('casts/'),
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validate
        $request->validate([
                'id'  => 'required|uuid',
                'name' => 'nullable|max:40',
                'year' => 'nullable|numeric',
                'runtime' => 'nullable|numeric',
                'rate' => 'nullable|numeric|between:0,99.99',
            ]);
    
        $update = Series::find($request->id);
    
        if (is_null($update)) {
            abort(404);
        }
    
        $update->t_name    = $request->name;
        $update->t_year    = $request->year;
        $update->t_desc    = $request->overview;
        if ($request->genres !== 'undefined') {
            $update->t_genre   = $request->genres;
        }
        $update->t_rate    = $request->rate;
        
        if (!empty($request->file('poster'))) {
            $name_poster = '/' . md5($request->file('poster')->getClientOriginalName() . microtime()) . '.jpg';
            $img1 = \Image::make($request->poster)->widen(300)->encode('jpg');
            Storage::disk('s3')->put('posters/' . $name_poster, $img1);
            $update->t_poster = $name_poster;
        }
        if (!empty($request->file('backdrop'))) {
            $name_backdrop = '/' . md5($request->file('backdrop')->getClientOriginalName() . microtime()) . '.jpg';
            $img2 = \Image::make($request->backdrop)->encode('jpg');
            Storage::disk('s3')->put('backdrops/' . $name_backdrop, $img2->__toString());
            $update->t_backdrop = $name_backdrop;
        }
    
        // Cast decode
        $casts = json_decode($request->cast);
        // Delete all cast and add new update
        $delete = Casts_rules::where('casts_series', $request->id)->delete();
        foreach ($casts as $key => $value) {
            $cast = new Casts_rules;
            $cast->casts_id = $value->credit_id;
            $cast->casts_series = $request->id;
            $cast->save();
        }
    
        $update->save();
        return response()->json(['status' => 'success', 'message' => 'Successful update ' . $request->name]);
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSeries($id)
    {
        $delete = Series::find($id);

        if (is_null($delete)) {
            return response()->json(['status' => 'faild', 'message' => 'There is no series found'], 404);
        }

        // Remove video
        Storage::disk('s3')->deleteDirectory('videos/' . $id . '/');
        // Remove subtitle
        Storage::disk('s3')->deleteDirectory('subtitles/' . $id . '/');

        $delete->delete();

        return response()->json(['status' => 'success', 'message' => 'successful delete ' . $delete->t_name]);
    }



    /**
     * Search movies by name or id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function searchSeries(Request $request)
    {
        $request->validate([
            'query' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
        ]);

        $getSeries = series::selectRaw('
                series.t_id AS id,
                series.t_name AS name,
                series.t_poster AS poster,
                series.t_year AS year,
                series.created_at,
                series.updated_at,
                tops.series_id')
            ->leftJoin('tops', 'tops.series_id', '=', 'series.t_id')
            ->where('series.t_name', 'like', $request->input('query') . '%')
            ->get();

        if ($getSeries->isEmpty()) {
            $getSeries = null;
        }

        return response()->json(
            ['data' => [
                'series' => $getSeries,
                'cdn' => [
                    'cdn_poster' => Storage::disk('s3')->url('posters/'),
                ]]],
            200
        );
    }

    /**
     * Delete episode
     *
     * @param [type] $id
     * @return void
     */
    public function deleteEpisode($id)
    {
        $delete = Episode::find($id);

        if (is_null($delete)) {
            return response()->json(['message' => 'There is no episode found'], 422);
        } else {
            // Remove video
            $get_videos = Video::where('episode_id', $id)->get();
            foreach ($get_videos as $video) {
                Storage::disk('s3')->delete('videos/' . $delete->e_series . '/' . $video->vidoe_url);
            }

            // Remove subtitle
            $get_subtitle = Subtitle::where('episode_id', $id)->get();
            foreach ($get_subtitle as $subtitle) {
                Storage::disk('s3')->delete('subtitles/' . $delete->e_series . '/' . $subtitle->s_name);
            }

            $delete->delete();

            return response()->json(['status' => 'success', 'message' => 'Successful delete'], 200);
        }
    }
}
