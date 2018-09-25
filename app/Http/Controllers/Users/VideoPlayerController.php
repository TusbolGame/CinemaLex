<?php

namespace App\Http\Controllers\Users;

use App\Episode;
use App\Helpers;
use App\Http\Controllers\Controller;
use App\Movie;
use App\Recently_watched;
use App\Report;
use App\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoPlayerController extends Controller
{

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function getMovieVideo($id)
    {
        // Check this movie is exist
        $checkAlreadyMovie = Movie::where('m_id', $id)->first();
        if (!is_null($checkAlreadyMovie)) {

            // Get Name and Vidoes, Recently watch of user
            $getMovieVideo = DB::select('
                                SELECT
                                movies.m_id AS id,
                                movies.m_name AS name,
                                videos.v_id AS video_id,
                                videos.video_url AS video,
                                videos.type,
                                videos.resolution AS resolution,
                                recently_watcheds.current_time,
                                recently_watcheds.duration_time
                                FROM movies
                                INNER JOIN videos ON videos.movie_id = movies.m_id
                                LEFT JOIN recently_watcheds  ON  recently_watcheds.movie_id = movies.m_id AND recently_watcheds.uid = "' . Auth::id() . '"
                                WHERE movies.m_id = "' . $id . '"
                                GROUP BY videos.v_id
                                ');

            // Get subtitle of movie
            $getMovieSubtitle = DB::table('subtitles')
                ->select('name AS subtitle_name', 'language AS subtitle_language')
                ->where('movie_id', $id)
                ->get();

            // Suggestion Next Movie
            $getSuggestionMovie = DB::table('movies')
                ->select('m_id AS id', 'm_name AS name', 'm_backdrop AS backdrop')
                ->where('m_id', '<>', $id)
                ->orderBy(DB::raw('RAND()'))
                ->first();

            // Check if there is subtitle, if there not set null
            if ($getMovieSubtitle->isEmpty()) {
                $getMovieSubtitle = null;
            }

            return response()->json(
                ['status' => 'success',
                    'data' => [
                        'video' => $getMovieVideo,
                        'subtitle' => $getMovieSubtitle,
                        'suggestion' => $getSuggestionMovie,
                        'cdn' => [
                            'cdn_video' => Helpers::getAwsUrl('video'),
                            'cdn_backdrop' => Helpers::getAwsUrl('backdrop'),
                            'cdn_subtitle' => Helpers::getAwsUrl('subtitle'),
                        ],
                    ]]
            );
        } else {
            return response()->json(['status' => 404], 404);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function getEpisodeVideo(Request $request)
    {
        $request->validate([
            'episode_id' => 'uuid',
            'series_id' => 'required|uuid',
            'type' => 'required|alpha|max:4',
        ]);

        // Check if the series is exist already
        $checkAlreadySeries = Series::find($request->input('series_id'));

        if (is_null($checkAlreadySeries)) {
            return response()->json(['status' => 404], 404);
        }

        // Get All season
        $getAllSeason = DB::select('
                                SELECT
                                episodes.id AS id,
                                episodes.name AS name,
                                episodes.backdrop AS backdrop,
                                episodes.series_id AS series_id,
                                episodes.episode_number AS episode_number,
                                episodes.season_number AS season_number,
                                episodes.overview AS overview,
                                recently_watcheds.current_time,
                                recently_watcheds.duration_time
                                FROM episodes
                                LEFT JOIN recently_watcheds ON recently_watcheds.episode_id = episodes.id AND recently_watcheds.uid = "' . Auth::id() . '"
                                WHERE episodes.series_id = "' . $request->input('series_id') . '"
                                ORDER BY season_number, episode_number ASC
                                ');

        $season = [];
        $next_season = null;
        // Check if there is no cast
        if (empty($getAllSeason)) {
            $season = null;
        } else {
            // Sort season and episode
            for ($i = 1; $i < count($getAllSeason); $i++) {
                for ($x = 0; $x < count($getAllSeason); $x++) {
                    if ($getAllSeason[$x]->season_number == $i) {
                        $season[$i][] = $getAllSeason[$x];
                    }
                }
            }
        }

        // if the episode choose from out list episode then is (cur)
        // if the episode choose from list episode then is (sp)
        if ($request->input('type') === 'cur') {

            // Get first episode id
            $getEpisodeNumber = DB::table('episodes')->select('id', 'season_number')->where('series_id', $request->input('series_id'))->orderBy('id', 'ASC')->first();

            // Get Name and Vidoes, Recently watch of user
            $getEpisodeVideo = DB::select('
                                SELECT
                                episodes.id AS id,
                                episodes.name AS name,
                                episodes.backdrop AS backdrop,
                                episodes.series_id AS series_id,
                                episodes.episode_number AS episode_number,
                                episodes.season_number AS season_number,
                                videos.v_id AS video_id,
                                videos.video_url AS video,
                                videos.resolution AS resolution,
                                videos.type,
                                recently_watcheds.current_time,
                                recently_watcheds.duration_time
                                FROM episodes
                                INNER JOIN videos ON videos.episode_id = episodes.id
                                LEFT JOIN recently_watcheds  ON  recently_watcheds.episode_id =  episodes.id AND recently_watcheds.uid = "' . Auth::id() . '"
                                WHERE episodes.id = "' . $getEpisodeNumber->id . '"
                                GROUP BY videos.v_id');

            // Get subtitle of episode
            $getSeriesSubtitle = DB::table('subtitles')
                ->select('name AS subtitle_name', 'language AS subtitle_language')
                ->where('episode_id', $getEpisodeNumber->id)
                ->get();

            // Get next season
            $getNextSeason = DB::table('episodes')
                ->select('id', 'name', 'backdrop', 'overview', 'episode_number', 'season_number', 'series_id')
                ->where('season_number', $getEpisodeNumber->season_number + 1)
                ->groupBy('episode_number')
                ->orderBy('episode_number', 'ASC')
                ->first();

            // Check if there is subtitle, if there not set null
            if ($getSeriesSubtitle->isEmpty()) {
                $getSeriesSubtitle = null;
            }

            return response()->json(
                ['status' => 'success',
                    'data' => [
                        'episode' => $getEpisodeVideo,
                        'subtitle' => $getSeriesSubtitle,
                        'season' => $season,
                        'next_season' => $getNextSeason,
                        'cdn' => [
                            'cdn_video' => Helpers::getAwsUrl('video'),
                            'cdn_backdrop' => Helpers::getAwsUrl('backdrop'),
                            'cdn_subtitle' => Helpers::getAwsUrl('subtitle'),
                        ],
                    ]]
            );
        } elseif ($request->input('type') === 'sp') {

            // Get first episode id
            $getEpisodeNumber = DB::table('episodes')->select('id', 'season_number')->where('series_id', $request->input('series_id'))->orderBy('id', 'ASC')->first();

            // Get Name and Vidoes, Recently watch of user
            $getEpisodeVideo = DB::select('
                                SELECT
                                episodes.id AS id,
                                episodes.name AS name,
                                episodes.backdrop AS backdrop,
                                episodes.series_id AS series_id,
                                episodes.episode_number AS episode_number,
                                episodes.season_number AS season_number,
                                videos.v_id AS video_id,
                                videos.video_url AS video,
                                videos.resolution AS resolution,
                                videos.type,
                                recently_watcheds.current_time,
                                recently_watcheds.duration_time
                                FROM episodes
                                LEFT JOIN recently_watcheds ON recently_watcheds.episode_id = episodes.id AND recently_watcheds.uid = "' . Auth::id() . '"
                                JOIN videos ON videos.episode_id = episodes.id
                                WHERE episodes.id = "' . $request->input('episode_id') . '" AND episodes.series_id = "' . $request->input('series_id') . '"
                                GROUP BY videos.v_id');

            // Get subtitle of episode
            $getSeriesSubtitle = DB::table('subtitles')
                ->select('name AS subtitle_name', 'language AS subtitle_language')
                ->where('episode_id', $request->input('episode_id'))
                ->get();

            // Get Season Epiosde
            $getNextSeason = DB::table('episodes')
                ->select('id', 'name', 'backdrop', 'overview', 'episode_number', 'season_number', 'series_id')
                ->where('season_number', $getEpisodeNumber->season_number + 1)
                ->groupBy('episode_number')
                ->orderBy('episode_number', 'ASC')
                ->first();

            // Check if there is subtitle, if there not set null
            if ($getSeriesSubtitle->isEmpty()) {
                $getSeriesSubtitle = null;
            }

            return response()->json(
                ['status' => 'success',
                    'data' => [
                        'episode' => $getEpisodeVideo,
                        'subtitle' => $getSeriesSubtitle,
                        'season' => $season,
                        'next_season' => $getNextSeason,
                        'cdn' => [
                            'cdn_video' => Helpers::getAwsUrl('video'),
                            'cdn_backdrop' => Helpers::getAwsUrl('backdrop'),
                            'cdn_subtitle' => Helpers::getAwsUrl('subtitle'),
                        ],
                    ]]
            );
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function movieReport(Request $request)
    {
        $request->validate([
            'type' => 'required|numeric',
            'details' => 'nullable|max:100',
            'id' => 'required|uuid',
        ]);

        $add = new Report();
        $add->report_type = $request->type;
        $add->report_details = $request->details;
        $add->report_userid = Auth::user()->id;
        $add->report_movie = $request->id;
        $add->save();

        return response()->json(['status' => 'success']);
    }

    public function seriesReport(Request $request)
    {
        $request->validate([
            'type' => 'required|numeric',
            'details' => 'nullable|max:100',
            'episode_id' => 'required|uuid',
            'series_id' => 'required|uuid',
        ]);

        $add = new Report();
        $add->report_type = $request->type;
        $add->report_details = $request->details;
        $add->report_userid = Auth::user()->id;
        $add->report_episode = $request->episode_id;
        $add->report_series = $request->series_id;
        $add->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Store recently time episode
     *
     * @return \Illuminate\Http\Response
     */
    public function setRecentlyTimeEpiosde(Request $request)
    {
        $request->validate([
            'current_time' => 'required|numeric',
            'duration_time' => 'required|numeric',
            'episode_id' => 'required|uuid',
            'series_id' => 'required|uuid',
        ]);

        $recently = Recently_watched::join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->where('recently_watcheds.uid', Auth::id())
            ->where('episodes.id', '=', $request->episode_id)
            ->where('episodes.series_id', '=', $request->series_id)
            ->orderBy('episodes.episode_number', 'DESC')
            ->first();

        if (!is_null($recently)) {
            $recently->episode_id = $request->episode_id;
            $recently->current_time = $request->current_time;
            $recently->duration_time = $request->duration_time;
            $recently->save();
            return response()->json(['status' => 'success']);
        } else {
            $store = new Recently_watched;
            $store->episode_id = $request->episode_id;
            $store->series_id = $request->series_id;
            $store->uid = Auth::id();
            $store->current_time = $request->current_time;
            $store->duration_time = $request->duration_time;
            $store->save();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed'], 404);
    }

    /**
     * Store recently time episode
     *
     * @return \Illuminate\Http\Response
     */
    public function setRecentlyTimeMovie(Request $request)
    {
        $request->validate([
            'current_time' => 'required|numeric',
            'duration_time' => 'required|numeric',
            'movie_id' => 'required|uuid',
        ]);

        $recently = Recently_watched::where('uid', Auth::id())->where('movie_id', $request->movie_id)->first();

        if (!is_null($recently)) {
            $recently->current_time = $request->current_time;
            $recently->save();
            return response()->json(['status' => 'success']);
        } else {
            $store = new Recently_watched;
            $store->movie_id = $request->movie_id;
            $store->uid = Auth::id();
            $store->current_time = $request->current_time;
            $store->duration_time = $request->duration_time;
            $store->save();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed'], 404);
    }
}
