<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Series;
use App\Helpers;

class SeriesController extends Controller
{

    /**
     * Get all series
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSeries()
    {
        $seriesQuery = DB::select('
                      SELECT
                      series.t_id AS id,
                      series.t_name AS name, 
                      series.t_desc AS overview, 
                      series.t_backdrop AS backdrop, 
                      series.t_genre AS genre, 
                      series.t_year AS year, 
                      series.t_rate AS rate, 
                      series.t_poster AS poster, 
                      series.t_age AS age,
                      u2.current_time,
                      u2.duration_time,
                      CASE
                      WHEN u1.id IS NULL THEN false
                      ELSE true
                      END AS "is_favorite",
                      CASE
                      WHEN u3.id IS NULL THEN false
                      ELSE true
                      END AS "is_like",
                      CASE
                      WHEN u4.series_id IS NULL THEN false
                      ELSE true
                      END AS "already_episode"
                      FROM series
                      LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' .Auth::id(). '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' .Auth::id(). '"
                      LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' .Auth::id(). '"
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE series.t_age <> "G"
                      GROUP BY series.t_id ASC
                      LIMIT 100');

        // Check if there is no series
        if (empty($seriesQuery)) {
            $seriesQuery = null;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'series' => $seriesQuery,
                'cdn'    => [
                    'cdn_poster' => Helpers::getAwsUrl('poster'),
                ]
            ]]);
    }

    /**
     * Get series details
     *
     * @param [type] $id
     * @return void
     */
    public function getSeriesDetails($id)
    {
        //Check if moive already
        $check = Series::find($id);

        if (is_null($check)) {
            return response()->json(['status' => 404], 404);
        }

        $seriesQuery = DB::select('
                      SELECT DISTINCT
                      series.t_id AS id,
                      series.t_name AS name, 
                      series.t_desc AS overview, 
                      series.t_backdrop AS backdrop, 
                      series.t_genre AS genre, 
                      series.t_year AS year, 
                      series.t_rate AS rate, 
                      series.t_poster AS poster, 
                      series.t_age AS age,
                      u2.current_time,
                      u2.duration_time,
                      CASE
                      WHEN u1.id IS NULL THEN false
                      ELSE true
                      END AS "is_favorite",
                      CASE
                      WHEN u3.id IS NULL THEN false
                      ELSE true
                      END AS "is_like",
                      CASE
                      WHEN u4.series_id IS NULL THEN false
                      ELSE true
                      END AS "already_episode"
                      FROM series
                      LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' .Auth::id(). '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' .Auth::id(). '"
                      LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' .Auth::id(). '"
                      LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                      WHERE t_id = "'. $id .'"
                      LIMIT 1');

        // Check if there is no series
        if (empty($seriesQuery)) {
            $seriesQuery = null;
        }


        // Get casts
        $getSeriesCast = DB::table('casts')
                    ->select('casts.c_id AS id', 'casts.c_name AS name', 'casts.c_image AS image')
                    ->join('casts_rules', 'casts_rules.casts_id', '=', 'casts.credit_id')
                    ->where('casts_series', '=', $id)
                    ->get();

        // Check if there is no cast
        if ($getSeriesCast->isEmpty()) {
            $getSeriesCast = null;
        }

        // Get casts
        $getAllSeason = DB::table('episodes')
                    ->where('series_id', $id)
                    ->orderByRaw('season_number, episode_number ASC')
                    ->get();


        $season = [];

        // Check if there is no cast
        if ($getAllSeason->isEmpty()) {
            $season = null;
        } else {

        // Sort season and episode
            for ($i=1; $i < count($getAllSeason); $i++) {
                for ($x=0; $x < count($getAllSeason); $x++) {
                    if ($getAllSeason[$x]->season_number == $i) {
                        $season[$i][] = $getAllSeason[$x];
                    }
                }
            }
        }
       
       
        return response()->json([
            'status' => 'success',
            'data'   => [
                'series' => $seriesQuery[0],
                'casts'  => $getSeriesCast,
                'season' => $season,
                'cdn'    => [
                    'cdn_poster' => Helpers::getAwsUrl('poster'),
                    'cdn_backdrop' => Helpers::getAwsUrl('backdrop'),
                    'cdn_cast' => Helpers::getAwsUrl('cast')
                ]
            ]]);
    }





    /**
     * Sort by trending and genre
     *
     * @return \Illuminate\Http\Response
     */

    public function sortSeries(Request $request)
    {
        $request->validate([
            'trending' => 'required|numeric|between:1,5',
            'genre' => 'required|string|max:15'
        ]);

        // Check if genre request equal the array genre
        $genreArray = ['all', 'action', 'action', 'adventure', 'animation', 'biography', 'comedy', 'crime', 'documentary', 'drama', 'family', 'fantasy', 'history', 'horror', 'music', 'mystery', 'romance', 'sci-fi', 'sport', 'thriller', 'war'];
        if (!in_array($request->input('genre'), $genreArray)) {
            return response()->json(['status' => 'error', 'message' => 'Genre not found'], 400);
        }
        
        if ($request->input('genre') === 'all') {
            $request->genre = "";
        }

        if ($request->input('trending') === 1) {
            $trending = 't_id';
        } elseif ($request->input('trending') === 2) {
            $trending = 't_year';
        } elseif ($request->input('trending') === 3) {
            $trending = 't_rate';
        } elseif ($request->input('trending') === 4) {
            $trending = 'likes.series_id';
        }
       
        $seriesQuery = DB::select('
                            SELECT DISTINCT 
                            series.t_id AS id,
                            series.t_name AS name, 
                            series.t_desc AS overview, 
                            series.t_backdrop AS backdrop, 
                            series.t_genre AS genre, 
                            series.t_year AS year, 
                            series.t_rate AS rate, 
                            series.t_poster AS poster, 
                            series.t_age AS age,
                            u2.current_time,
                            u2.duration_time,
                            CASE
                            WHEN u1.id IS NULL THEN false
                            ELSE true
                            END AS "is_favorite",
                            CASE
                            WHEN u3.id IS NULL THEN false
                            ELSE true
                            END AS "is_like",
                            CASE
                            WHEN u4.series_id IS NULL THEN false
                            ELSE true
                            END AS "already_episode"
                            FROM series
                            LEFT JOIN likes ON likes.series_id = series.t_id
                            LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' .Auth::id(). '"
                            LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' .Auth::id(). '"
                            LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' .Auth::id(). '"
                            LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                            WHERE series.t_genre LIKE "'. $request->genre .'%"
                            ORDER BY '.$trending. ' DESC
                            LIMIT 100');

        // Check if there is no series
        if (empty($seriesQuery)) {
            $seriesQuery = null;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'series' => $seriesQuery,
                'cdn'    => [
                    'cdn_poster' => Helpers::getAwsUrl('poster'),
                ]
            ]]);
    }
}
