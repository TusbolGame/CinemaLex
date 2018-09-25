<?php

namespace App\Http\Controllers\Users;

use App\Appearance;
use App\Helpers;
use App\Http\Controllers\Controller;
use App\Movie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * This mix of series and movies
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomeResult()
    {
        // This array to for loop to determine the type and genre
        $genres = [
            [
                'genre' => 'Action',
                'type' => 'movies',
            ],
            [
                'genre' => 'Drama',
                'type' => 'series',
            ],
            [
                'genre' => 'Action & Adventure',
                'type' => 'series',
            ],
            [
                'genre' => 'Crime',
                'type' => 'movies',
            ],
            [
                'genre' => 'Fantasy',
                'type' => 'movies',
            ],
            [
                'genre' => 'Drama',
                'type' => 'movies',
            ],
            [
                'genre' => 'Crime',
                'type' => 'series',
            ],
            [
                'genre' => 'Family',
                'type' => 'series',
            ],
            [
                'genre' => 'Action',
                'type' => 'movies',
            ],
            [
                'genre' => 'Horror',
                'type' => 'movies',
            ],
            [
                'genre' => 'Mystery',
                'type' => 'series',
            ],
            [
                'genre' => 'Romance',
                'type' => 'movies',
            ],
            [
                'genre' => 'Sci-Fi & Fantasy',
                'type' => 'series',
            ],
            [
                'genre' => 'Thriller',
                'type' => 'movies',
            ],
            [
                'genre' => 'MystWar & Politicsery',
                'type' => 'series',

            ],
            [
                'genre' => 'Documentary',
                'type' => 'movies',
            ],
            [
                'genre' => 'Comedy',
                'type' => 'movies',
            ],
            [
                'genre' => 'Comedy',
                'type' => 'series',
            ],
        ];

        // Execute query and push it in array
        $getMasByGenre = [];
        for ($a = 0; $a < count($genres); $a++) {
            if ($genres[$a]['type'] === 'movies') {
                $movieQuery = DB::select('
                      SELECT
                      "movie" AS type,
                      movies.m_id AS id,
                      movies.m_name AS name,
                      movies.m_poster AS poster,
                      movies.m_desc AS overview,
                      movies.m_runtime AS runtime,
                      movies.m_year AS year,
                      movies.m_genre AS genre,
                      movies.m_rate AS rate,
                      movies.m_backdrop AS backdrop,
                      movies.m_age AS age,
                      u2.current_time,
                      u2.duration_time,
                      CASE
                      WHEN u1.id IS NULL THEN false
                      ELSE true
                      END AS "is_favorite",
                      CASE
                      WHEN u3.id IS NULL THEN false
                      ELSE true
                      END AS "is_like"
                      FROM movies
                      LEFT JOIN collection_lists AS u1  ON u1.movie_id = movies.m_id AND u1.uid = "' . Auth::id() . '"
                      LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = movies.m_id AND u2.uid = "' . Auth::id() . '"
                      LEFT JOIN likes AS u3  ON u3.movie_id = movies.m_id AND u3.uid = "' . Auth::id() . '"
                      WHERE movies.m_age <> "G"  AND  movies.m_genre LIKE "' . $genres[$a]['genre'] . '%"
                      GROUP BY movies.m_id DESC
                      LIMIT 10');

                array_push($getMasByGenre, [
                    'list' => $movieQuery,
                    'genre' => $genres[$a]['genre'],
                    'type' => 'Movies',
                ]);
            } elseif ($genres[$a]['type'] === 'series') {

                // if type eqaule series get the series by genre
                $seriesQuery = DB::select('
                                SELECT
                                "series" AS type,
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
                                LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id AND u1.uid = "' . Auth::id() . '"
                                LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id AND u2.uid = "' . Auth::id() . '"
                                LEFT JOIN likes AS u3  ON u3.series_id = series.t_id AND u3.uid = "' . Auth::id() . '"
                                LEFT JOIN episodes AS u4 ON u4.series_id = series.t_id
                                WHERE series.t_age <> "G"  AND  series.t_genre LIKE  "' . $genres[$a]['genre'] . '%"
                                GROUP BY series.t_id DESC
                                LIMIT 10');

                array_push($getMasByGenre, [
                    'list' => $seriesQuery,
                    'genre' => $genres[$a]['genre'],
                    'type' => 'Series',
                ]);
            }
        }

        // Get top movies and series
        $getTopMas = DB::select('(SELECT
                                "movie" AS type,
                                movies.m_id AS id,
                                movies.m_name AS name,
                                movies.m_poster AS poster,
                                movies.m_desc AS overview,
                                movies.m_year AS year,
                                movies.m_genre AS genre,
                                movies.m_rate AS rate,
                                movies.m_backdrop AS backdrop,
                                movies.m_age AS age,
                                u2.current_time,
                                u2.duration_time,
                                CASE
                                WHEN u1.id IS NULL THEN false
                                ELSE true
                                END AS "is_favorite"
                                FROM tops
                                INNER JOIN movies  ON movies.m_id = tops.movie_id
                                LEFT JOIN collection_lists AS u1  ON u1.movie_id = movies.m_id
                                LEFT JOIN recently_watcheds AS u2 ON u2.movie_id = movies.m_id
                                GROUP BY movies.m_id DESC)
                                UNION
                                (SELECT
                                "series" AS type,
                                series.t_id AS id,
                                series.t_name AS name,
                                series.t_poster AS poster,
                                series.t_desc AS overview,
                                series.t_year AS year,
                                series.t_genre AS genre,
                                series.t_rate AS rate,
                                series.t_backdrop AS backdrop,
                                series.t_age AS age,
                                u2.current_time,
                                u2.duration_time,
                                CASE
                                WHEN u1.id IS NULL THEN false
                                ELSE true
                                END AS "is_favorite"
                                FROM tops
                        	    INNER JOIN series  ON series.t_id = tops.series_id
                                LEFT JOIN collection_lists AS u1  ON u1.series_id = series.t_id
                                LEFT JOIN recently_watcheds AS u2 ON u2.series_id = series.t_id
                                GROUP BY series.t_id DESC)');

        if (empty($getTopMas)) {
            $getTopMas = null;
        }

        // Last 3 Recently Watched
        $getRecentlyQuery = DB::select('
                      (SELECT
                      "movie" AS type,
                      movies.m_id AS id,
                      movies.m_name AS name,
                      movies.m_backdrop AS backdrop,
                      recently_watcheds.current_time,
                      recently_watcheds.duration_time
                      FROM recently_watcheds
                      JOIN movies ON movies.m_id = recently_watcheds.movie_id
                      WHERE recently_watcheds.uid = "' . Auth::id() . '"
                      )
                      UNION
                      (SELECT
                      "series" AS type,
                      episodes.series_id,
                      episodes.name,
                      episodes.backdrop,
                      recently_watcheds.current_time,
                      recently_watcheds.duration_time
                      FROM recently_watcheds
                      JOIN episodes ON episodes.id = recently_watcheds.episode_id
                      WHERE recently_watcheds.uid = "' . Auth::id() . '"
                      ) LIMIT 3');

        return response()->json([
            'status' => 'success',
            'data' => [
                'data' => $getMasByGenre,
                'top' => $getTopMas,
                'recenlty' => $getRecentlyQuery,
                'cdn' => ['poster' => Helpers::getAwsUrl('poster'),
                    'backdrop' => Helpers::getAwsUrl('backdrop'),
                ],
            ]], 200);
    }

    /**
     * Get App Details
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppDetails()
    {
        return response()->json([
            'status' => 'success',
            'data' => Appearance::first(),
        ]);
    }
}
