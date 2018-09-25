<?php

namespace App\Http\Controllers\Admin;

use App\Casts;
use App\Casts_rules;
use App\Http\Controllers\Controller;
use App\Movie;
use App\Subtitle;
use App\Video;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \Done\Subtitles\Subtitles;

class MovieController extends Controller
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
    public function getAllMovies()
    {
        $getMovies = Movie::selectRaw('
                        movies.m_id AS id,
                        movies.m_name AS name,
                        movies.m_poster AS poster,
                        movies.m_year AS year,
                        movies.created_at,
                        movies.updated_at,
                        tops.movie_id')
            ->leftJoin('tops', 'tops.movie_id', '=', 'movies.m_id')
            ->orderBy('movies.created_at', 'DESC')
            ->paginate(15);

        // Check if there is no result
        if (empty($getMovies->all())) {
            $getMovies = null;
        }
        return response()->json(
            ['data' => [
                'movies' => $getMovies,
                'cdn' => [
                    'cdn_poster' => Storage::disk('s3')->url('posters/'),
                ]]],
            200
        );
    }

    public function moviedbApi(Request $request)
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

        // Get details from themoviedb
        $detils = 'https://api.themoviedb.org/3/movie/' . $request->id . '?api_key=' . $token . '&language=en-US';
        $trailer = 'https://api.themoviedb.org/3/movie/' . $request->id . '/videos?api_key=' . $token . '&language=en-US';

        // Check if there movie or not
        try {
            $res_movies = $client->get($detils)->getBody();
            $data_movies = json_decode($res_movies, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['status' => 'failed', 'message' => 'There is no movie like this id.'], 422);
        }
        // Get video trailer
        $res_trailer = $client->get($trailer)->getBody();
        $data_trailer = json_decode($res_trailer, true);
        // Get the backdrop and poster image from api themoviedb and
        $name_poster = (!isset($data_movies['poster_path']) ? 'none' : str_random(20) . '.jpg');
        $poster = (!isset($data_movies['poster_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w500/' . $data_movies['poster_path'])->getBody());

        $name_backdrop = (!isset($data_movies['backdrop_path']) ? 'none' : str_random(20) . '.jpg');
        $backdrop = (!isset($data_movies['backdrop_path']) ? 'none' : $client->get('https://image.tmdb.org/t/p/w1280/' . $data_movies['backdrop_path'])->getBody());

        // If there no movies image @return json "Error"
        if ($name_poster === 'none' || $name_backdrop === 'none') {
            return response()->json(['status' => 'error', 'msg' => 'Please use custom upload, because no image for this movie.']);
        } else {
            // Change image size and upload it to S3 Storage
            $img1 = \Image::make($poster)->encode('jpg');
            $img2 = \Image::make($backdrop)->encode('jpg');
            Storage::disk('s3')->put('posters/' . $name_poster, $img1->__toString());
            Storage::disk('s3')->put('backdrops/' . $name_backdrop, $img2->__toString());
        }

        //Store data
        $store = new Movie;
        $store->m_name = $data_movies['title'];
        $store->m_desc = $data_movies['overview'];
        $store->m_year = substr($data_movies['release_date'], 0, 4);
        $store->m_runtime = $data_movies['runtime'];
        $store->m_rate = $data_movies['vote_average'];
        $store->m_youtube = (!isset($data_trailer['results'][0]['key']) ? null : 'https://www.youtube.com/watch?v=' . $data_trailer['results'][0]['key']);
        $store->m_backdrop = $name_backdrop;
        $store->m_poster = $name_poster;
        $store->m_age = $request->age;
        foreach ($data_series['genres'] as $key => $value) {
            $genre1[] = $value['name'];
            $genre2 = implode(", ",$genre1);
        }
        $store->m_genre = $genre2;
        $store->save();

        // Get the casts details from TheMovieDB and store it in database
        $casts = 'https://api.themoviedb.org/3/movie/' . $request->id . '/credits?api_key=' . $token . '&language=en-US';
        $res_casts = $client->get($casts)->getBody();
        $data_casts = json_decode($res_casts, true);

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
                Storage::disk('s3')->put('casts/' . $store->m_id . '/' . $image_name, $image_encode->__toString());

                //If there same cast in db
                $check_cast = Casts::where('credit_id', $value['credit_id'])->first();
                if ($check_cast) {
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_movies = $store->m_id;
                    $casts_rule->save();
                } else {
                    $casts_store = new Casts;
                    $casts_store->credit_id = $value['credit_id'];
                    $casts_store->c_name = $value['name'];
                    $casts_store->c_image = $store->m_id . '/' . $image_name;
                    $casts_store->save();
                    //Casts rules
                    $casts_rule = new Casts_rules;
                    $casts_rule->casts_id = $value['credit_id'];
                    $casts_rule->casts_movies = $store->m_id;
                    $casts_rule->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Successful store movie details in database', 'id' => $store->m_id], 200);
    }

    public function movieUploadS3(Request $request)
    {
        // if there is video_link add it and save the resolution as 1024p
        if ($request->video_link !== 'undefined' && !empty($request->video_link)) {
            $video = new video();
            $video->resolution = '720';
            $video->movie_id = $request->id;
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
                $video->movie_id = $request->id;
                $video->type = 's3';
                $video->video_url = $request->id . '/' . $hash_name . '.' . $extension;
                $video->save();
            } elseif ($request->resolution_720 === 'true') {
                $video = new Video();
                $video->resolution = '1080';
                $video->movie_id = $request->id;
                $video->type = 's3';
                $video->video_url = $request->id . '/' . $hash_name . '.' . $extension;
                $video->save();
            } else {
                $video = new Video();
                $video->resolution = '720';
                $video->movie_id = $request->id;
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
                    $video->movie_id = $request->id;
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

    public function subtitleUploadS3(Request $request)
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
                $sub->movie_id = $request->id;
                $sub->save();
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Successful upload subtitles', 'data' => $sub]);
    }


    public function customUploadMovieDetails(Request $request)
    {
        $request->validate([
                'name' => 'required|max:50|regex:/^[a-z0-9 .\-]+$/i',
                'overview' => 'required|max:500',
                'year' => 'required|numeric|between:0,2030',
                'runtime' => 'required|numeric|between:0,500',
                'rate' => 'required|numeric|between:0,99.99',
                'youtube' => 'required|url',
                'genres' => 'required|max:100',
                'poster' => 'required|mimes:jpeg,jpg,png',
                'backdrop' => 'required|mimes:jpeg,jpg,png',
                'video' => 'mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv,webm',
                'video_link' => 'nullable|url',
            ]);

        // Upload images to s3
        $name_poster = md5($request->file('poster')->getClientOriginalName() . microtime()) . '.jpg';
        $name_backdrop = md5($request->file('backdrop')->getClientOriginalName() . microtime()) . '.jpg';
        $img1 = \Image::make($request->poster)->encode('jpg');
        $img2 = \Image::make($request->backdrop)->encode('jpg');
        Storage::disk('s3')->put('posters/' . $name_poster, $img1->__toString());
        Storage::disk('s3')->put('backdrops/' . $name_backdrop, $img2->__toString());
         
        //Store data
        $store = new Movie;
        $store->m_name = $request->name;
        $store->m_desc = $request->overview;
        $store->m_year = $request->year;
        $store->m_runtime = $request->runtime;
        $store->m_rate = $request->rate;
        $store->m_youtube = $request->youtube;
        $store->m_backdrop = $name_backdrop;
        $store->m_poster = $name_poster;
        $store->m_age = $request->age;
        $store->m_genre = str_replace(",", "-", $request->genres);
        $store->save();
        // Add casts
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
            Storage::disk('s3')->put('casts/' . $store->m_id . '/' . $img_name, $img->__toString());
            $casts_store = new Casts();
            $casts_store->credit_id = $value['id'];
            $casts_store->c_name = $value['name'];
            $casts_store->c_image = $store->m_id . '/' . $img_name;
            $casts_store->save();

            $casts_rule = new Casts_rules();
            $casts_rule->casts_id = $value['id'];
            $casts_rule->casts_movies = $store->m_id;
            $casts_rule->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Successful store movie details in database', 'id' => $store->m_id], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getMovieInfo($id)
    {
        $getMovie = DB::table('movies')
                    ->selectRaw('movies.m_id AS id, movies.m_name AS name, movies.m_poster AS poster, movies.m_desc AS overview, movies.m_runtime AS runtime, movies.m_year AS year, movies.m_genre AS genre, movies.m_rate AS rate, movies.m_backdrop AS backdrop, movies.m_age AS age')
                    ->where('m_id', $id)
                    ->first();

        if (is_null($getMovie)) {
            abort(404);
        }
        
        $getAllCasts = Casts::select('credit_id AS new_credit_id', 'c_image AS image', 'c_name AS name')->orderBy('c_name', 'ASC')->get();
        $getCastOfMovie = DB::table(DB::raw('casts'))
            ->select('casts.credit_id', 'casts.c_name AS name', 'casts.c_image AS image')
            ->join('casts_rules', 'casts_rules.casts_id', '=', 'casts.credit_id')
            ->where('casts_rules.casts_movies', $id)
            ->orderBy('c_name', 'ASC')
            ->get();

        return response()->json([
            'data' => [
                'movie' => $getMovie,
                'cast' => $getCastOfMovie,
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Validate
        $request->validate([
            'id'  => 'required|uuid',
            'year' => 'nullable|numeric',
            'runtime' => 'nullable|numeric',
            'rate' => 'nullable|numeric|between:0,99.99',
            'youtube' => 'nullable|url',
        ]);

        $update = Movie::find($request->id);

        if (is_null($update)) {
            abort(404);
        }

        $update->m_name    = $request->name;
        $update->m_year    = $request->year;
        $update->m_desc    = $request->overview;
        if ($request->genres !== 'undefined') {
            $update->m_genre   = $request->genres;
        }
        $update->m_runtime = $request->runtime;
        $update->m_rate    = $request->rate;
        $update->m_youtube = $request->youtube;
    
        if (!empty($request->file('poster'))) {
            $name_poster = '/' . md5($request->file('poster')->getClientOriginalName() . microtime()) . '.jpg';
            $img1 = \Image::make($request->poster)->widen(300)->encode('jpg');
            Storage::disk('s3')->put('posters/' . $name_poster, $img1);
            $update->m_poster = $name_poster;
        }
        if (!empty($request->file('backdrop'))) {
            $name_backdrop = '/' . md5($request->file('backdrop')->getClientOriginalName() . microtime()) . '.jpg';
            $img2 = \Image::make($request->backdrop)->encode('jpg');
            Storage::disk('s3')->put('backdrops/' . $name_backdrop, $img2->__toString());
            $update->m_backdrop = $name_backdrop;
        }

        // Cast decode
        $casts = json_decode($request->cast);
        // Delete all cast and add new update
        $delete = Casts_rules::where('casts_movies', $request->id)->delete();
        foreach ($casts as $key => $value) {
            $cast = new Casts_rules;
            $cast->casts_id = $value->credit_id;
            $cast->casts_movies = $request->id;
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
    public function deleteMovie($id)
    {
        $delete = Movie::find($id);

        if (is_null($delete)) {
            return response()->json(['status' => 'faild', 'message' => 'There is no movie found'], 404);
        }

        // Remove video
        Storage::disk('s3')->deleteDirectory('videos/' . $id . '/');
        // Remove subtitle
        Storage::disk('s3')->deleteDirectory('subtitles/' . $id . '/');

        $delete->delete();

        return response()->json(['status' => 'success', 'message' => 'successful delete ' . $delete->m_name]);
    }

    /**
     * Search movies by name or id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function searchMovie(Request $request)
    {
        $request->validate([
            'query' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
        ]);

        $getMovie = Movie::selectRaw('
                movies.m_id AS id,
                movies.m_name AS name,
                movies.m_poster AS poster,
                movies.m_year AS year,
                movies.created_at,
                movies.updated_at,
                tops.movie_id')
                ->leftJoin('tops', 'tops.movie_id', '=', 'movies.m_id')
                ->where('m_name', 'like', $request->input('query'). '%')
                ->get();

        if ($getMovie->isEmpty()) {
            $getMovie = null;
        }

        return response()->json(
            ['data' => [
                'movies' => $getMovie,
                'cdn' => [
                    'cdn_poster' => Storage::disk('s3')->url('posters/'),
                ]]],
            200
        );
    }
}
