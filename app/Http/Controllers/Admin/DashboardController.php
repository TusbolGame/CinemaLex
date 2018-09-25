<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class DashboardController extends Controller
{
    /**
        * Create a new controller instance.
        *
        * @return void
        */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin','manager']);
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');
    }

    /**
     * Get users status
     *
     * @return \Illuminate\Http\Response
     */

    public function usersAnalysisByDay()
    {

        // Users Analysis

        $activeUserDay = DB::table('users')
            ->selectRaw('"active" AS type, count(*) AS number, HOUR(users.created_at) AS hour')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE() AND users.period_end > NOW() ')
            ->groupBy('hour');
        
        $inactiveUserDay = DB::table('users')
            ->selectRaw('"inactive" AS type ,count(*) AS number, HOUR(users.created_at) AS hour')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 DAY) AND CURRENT_DATE() AND users.period_end < NOW() ')
            ->groupBy('hour')
            ->union($activeUserDay)
            ->get();
        
        $activeUserMonth = DB::table('users')
            ->selectRaw('"active" AS type, count(*) AS number, MONTHNAME(users.created_at) AS month')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND users.period_end > NOW() ')
            ->groupBy('month');
        
        $inactiveUserMonth = DB::table('users')
            ->selectRaw('"inactive" AS type ,count(*) AS number, MONTHNAME(users.created_at) AS month')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND users.period_end < NOW() ')
            ->groupBy('month')
            ->union($activeUserMonth)
            ->get();


        $activeUserYear = DB::table('users')
            ->selectRaw('"active" AS type, count(*) AS number, YEAR(users.created_at) AS year')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND users.period_end > NOW() ')
            ->groupBy('year');
        
        $inactiveUserYear = DB::table('users')
            ->selectRaw('"inactive" AS type ,count(*) AS number, YEAR(users.created_at) AS year')
            ->whereRaw('users.created_at BETWEEN (CURRENT_DATE() - INTERVAL 1 YEAR) AND CURRENT_DATE() AND users.period_end < NOW() ')
            ->groupBy('year')
            ->union($activeUserYear)
            ->get();

        // Top movie and seris
    
        // Hourly
        $getTopEpisodeDay = DB::table('recently_watcheds')
                    ->selectRaw('"episode" AS type, count(recently_watcheds.episode_id) AS count, episodes.name AS name, series.t_name AS series_name, HOUR(recently_watcheds.created_at) AS hour')
                    ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
                    ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
                    ->groupBy('recently_watcheds.episode_id')
                    ->limit(5);

        $getTopmMovieDay = DB::table('recently_watcheds')
                    ->selectRaw('"movie" AS type, count(recently_watcheds.movie_id) AS count, movies.m_name AS name, null AS series_name, HOUR(recently_watcheds.created_at) AS hour')
                    ->join('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
                    ->groupBy('recently_watcheds.movie_id')
                    ->union($getTopEpisodeDay)
                    ->limit(5)
                    ->get();

        // Monthly
        $getTopEpisodeMonth = DB::table('recently_watcheds')
                    ->selectRaw('"episode" AS type, count(recently_watcheds.episode_id) AS count, episodes.name AS name, series.t_name AS series_name, MONTHNAME(recently_watcheds.created_at) AS month')
                    ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
                    ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
                    ->groupBy('recently_watcheds.episode_id')
                    ->limit(5);

        $getTopmMovieMonth = DB::table('recently_watcheds')
                    ->selectRaw('"movie" AS type, count(recently_watcheds.movie_id) AS count, movies.m_name AS name, null AS series_name, MONTHNAME(recently_watcheds.created_at) AS month')
                    ->join('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
                    ->groupBy('recently_watcheds.movie_id')
                    ->union($getTopEpisodeMonth)
                    ->limit(5)
                    ->get();

        // Yearly
        $getTopEpisodeYear = DB::table('recently_watcheds')
                    ->selectRaw('"episode" AS type, count(recently_watcheds.episode_id) AS count, episodes.name AS name, series.t_name AS series_name, YEAR(recently_watcheds.created_at) AS year')
                    ->join('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
                    ->join('series', 'series.t_id', '=', 'recently_watcheds.series_id')
                    ->groupBy('recently_watcheds.episode_id')
                    ->limit(5);


        $getTopmMovieYear = DB::table('recently_watcheds')
                    ->selectRaw('"movie" AS type, count(recently_watcheds.movie_id) AS count, movies.m_name AS name, null AS series_name, YEAR(recently_watcheds.created_at) AS year')
                    ->join('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
                    ->groupBy('recently_watcheds.movie_id')
                    ->union($getTopEpisodeYear)
                    ->limit(5)
                    ->get();



      
        $top_movies = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.movie_id) AS movie_count,movies.m_name')
            ->leftJoin('movies', 'movies.m_id', '=', 'recently_watcheds.movie_id')
            ->groupBy('recently_watcheds.movie_id')
            ->orderByRaw('movie_count DESC')
            ->limit(10)
            ->get();

        $top_series = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.episode_id) AS series_count,episodes.name,t_name')
            ->leftJoin('episodes', 'episodes.id', '=', 'recently_watcheds.episode_id')
            ->leftJoin('series', 'series.t_id', '=', 'episodes.series_id')
            ->groupBy('recently_watcheds.episode_id')
            ->orderByRaw('series_count DESC')
            ->limit(10)
            ->get();

        $top_users = DB::table('recently_watcheds')
            ->selectRaw('count(recently_watcheds.uid) AS user_count,users.name')
            ->join('users', 'users.id', '=', 'recently_watcheds.uid')
            ->groupBy('recently_watcheds.uid')
            ->orderByRaw('user_count DESC')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => [
                    'day' =>  $inactiveUserDay,
                    'month' => $inactiveUserMonth,
                    'year' =>  $inactiveUserYear
                ],
                'top' => [
                    'day' => $getTopmMovieDay,
                    'month' => $getTopmMovieMonth,
                    'year' =>  $getTopmMovieYear
                ],
                'count' => [
                    'reports' => DB::table('reports')->count(),
                    'movies' => DB::table('movies')->count(),
                    'series' => DB::table('series')->count(),
                    'episodes' => DB::table('episodes')->count(),
                    'users' => DB::table('users')->count()
                ],
                'top_all_time' => [
                    'movies' => $top_movies,
                    'series' => $top_series,
                    'users' => $top_users
                ]
            ]
        ]);
    }
}
