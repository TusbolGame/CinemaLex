<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */


Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
});

Route::get('v1/', 'Users\HomeController@index')->name('home');

Route::get('v1/{vue_capture?}', function() {
    return view('home');
})->where('vue_capture', '[\/\w\.-]*');


Route::group(['middleware' => 'auth:admin'], function () {

    
    Route::get('/administrator', 'Admin\DashboardController@index')->name('dashboard');

    // Check permission
    Route::get('/api/admin/check/permission', 'Admin\CheckAdminPermission@checkPermission');

    // Dashboard analysis
    Route::get('/api/admin/get/dashboard/analysis', 'Admin\DashboardController@usersAnalysisByDay');

    // Movies manage
    Route::get('/api/admin/get/movies', 'Admin\MovieController@getAllMovies');
    Route::delete('/api/admin/delete/movie/{id}', 'Admin\MovieController@deleteMovie');
    Route::post('/api/admin/get/movie/search', 'Admin\MovieController@searchMovie');
    Route::post('/api/admin/new/movie/movieapi', 'Admin\MovieController@moviedbApi');
    Route::post('/api/admin/new/movie/movievideo', 'Admin\MovieController@movieUploadS3');
    Route::post('/api/admin/new/movie/moviesubtitle', 'Admin\MovieController@subtitleUploadS3');
    Route::post('/api/admin/new/movie/customupload', 'Admin\MovieController@customUploadMovieDetails');
    Route::get('/api/admin/get/movie/{id}', 'Admin\MovieController@getMovieInfo');
    Route::post('/api/admin/update/movie', 'Admin\MovieController@update');


    // Series manage
    Route::get('/api/admin/get/series', 'Admin\SeriesController@getAllSeries');
    Route::get('/api/admin/get/series/{id}', 'Admin\SeriesController@getSeries');
    Route::post('/api/admin/get/series/search', 'Admin\SeriesController@searchSeries');
    Route::delete('/api/admin/delete/series/{id}', 'Admin\SeriesController@deleteSeries');
    Route::post('/api/admin/new/series/moviedbapi', 'Admin\SeriesController@getSeriesByApi');
    Route::post('/api/admin/new/series/customupload', 'Admin\SeriesController@customUploadSeriesDetails');
    Route::get('/api/admin/get/series/season/{id}', 'Admin\SeriesController@getAllSeason');
    Route::post('/api/admin/new/series/episode/details', 'Admin\SeriesController@getEpisodeDetailsApi');
    Route::post('/api/admin/new/series/episode/video', 'Admin\SeriesController@UploadVideoToS3');
    Route::post('/api/admin/new/series/episode/subtitle', 'Admin\SeriesController@UploadSubtitleToS3');
    Route::get('/api/admin/get/series/info/{id}', 'Admin\SeriesController@getSeriesInfo');
    Route::delete('/api/admin/delete/series/episode/{id}', 'Admin\SeriesController@deleteEpisode');
    Route::post('/api/admin/update/series', 'Admin\SeriesController@update');

    // Tv manage
    Route::get('/api/admin/get/channels', 'Admin\TvController@getAllChannels');
    Route::post('/api/admin/new/channel/details', 'Admin\TvController@createChannelDetails');
    Route::post('/api/admin/new/channel/video', 'Admin\TvController@uploadChannelVideo');
    Route::delete('/api/admin/delete/channel/{id}', 'Admin\TvController@deleteChannel');

    // Tops manage
    Route::get('/api/admin/get/top', 'Admin\TopsController@getTopMas');
    Route::post('/api/admin/new/movie/top', 'Admin\TopsController@addMovieToTop');
    Route::post('/api/admin/new/series/top', 'Admin\TopsController@addSeriesToTop');
    Route::delete('/api/admin/delete/top/{id}', 'Admin\TopsController@deleteTop');


    // Actors manage
    Route::get('/api/admin/get/actors', 'Admin\ActorsController@getAllActors');
    Route::post('/api/admin/update/actors/', 'Admin\ActorsController@update');
    Route::post('/api/admin/new/actor', 'Admin\ActorsController@create');
    Route::delete('/api/admin/delete/actor/{id}', 'Admin\ActorsController@delete');

    // Reports manage
    Route::get('/api/admin/get/reports', 'Admin\ReportsController@getAllReports');
    Route::get('/api/admin/get/report/{id}', 'Admin\ReportsController@getReport');
    Route::delete('/api/admin/delete/report/{id}', 'Admin\ReportsController@deleteOneReport');
    Route::delete('/api/admin/delete/all/reports/{id}', 'Admin\ReportsController@deleteAllReports');
    Route::match(['put', 'patch'], '/api/admin/new/report/readit/{id}', 'Admin\ReportsController@readIt');

    // Subtitles manage
    Route::get('/api/admin/get/subtitles/movie/{id}', 'Admin\SubtitlesController@getMovieSubtitle');
    Route::get('/api/admin/get/subtitles/episode/{id}', 'Admin\SubtitlesController@getEpisodeSubtitle');
    Route::delete('/api/admin/delete/subtitle/{id}', 'Admin\SubtitlesController@deleteSubtitle');


    // Users manage
    Route::get('/api/admin/get/users', 'Admin\UsersController@getAllUsers');
    Route::get('/api/admin/get/users/inactivity', 'Admin\UsersController@getInactivityUsers');
    Route::get('/api/admin/get/users/activity', 'Admin\UsersController@getActivityUsers');    
    Route::post('/api/admin/get/users/search', 'Admin\UsersController@getUsersBySearch');
    Route::post('/api/admin/get/users/invoice', 'Admin\UsersController@getUserInvoice');
    Route::delete('/api/admin/delete/users/{id}', 'Admin\UsersController@delete');
    Route::get('/api/admin/get/user/details/{id}', 'Admin\UsersController@getUserDetails');
    Route::post('/api/admin/update/user', 'Admin\UsersController@update');

    // Admins users manage
    Route::get('/api/admin/get/settings/admins/users', 'Admin\AdminsUsersController@getAllAdmins');
    Route::delete('/api/admin/setting/delete/admin/user/{id}', 'Admin\AdminsUsersController@delete');
    Route::post('/api/admin/new/settings/admin/user', 'Admin\AdminsUsersController@create');
    Route::get('/api/admin/get/settings/user/details/{id}', 'Admin\AdminsUsersController@getAdminDetails');
    Route::post('/api/admin/settings/users/image', 'Admin\AdminsUsersController@updateImage');
    Route::post('/api/admin/update/settings/user', 'Admin\AdminsUsersController@updateDetails');

    // Backup manage
    Route::get('/api/admin/get/settings/backup', 'Admin\BackupController@getAll');
    Route::post('/api/admin/new/settings/backup', 'Admin\BackupController@newBackup');
    Route::post('/api/admin/download/settings/backup/download', 'Admin\BackupController@downloadBackup');
    Route::post('/api/admin/delete/settings/backup', 'Admin\BackupController@delete');

    // Theme manage
    Route::get('/api/admin/settings/theme', 'Admin\PluginController@get');
    Route::post('/api/admin/settings/theme/upload', 'Admin\PluginController@upload');
    Route::post('/api/admin/settings/theme/set', 'Admin\PluginController@set');
    Route::delete('/api/admin/settings/theme/delete/{id}', 'Admin\PluginController@delete')->where('id', '^[0-9]');

    // Footer manage
    Route::get('/api/admin/get/settings/appearance/footer', 'Admin\AppearancesController@get');
    Route::post('/api/admin/update/settings/appearance/footer', 'Admin\AppearancesController@update');

    // Profile manage
    Route::get('/api/admin/profile', 'Admin\ProfileController@getUser');
    Route::post('/api/admin/profile/image', 'Admin\ProfileController@updateImage');
    Route::post('/api/admin/profile/details', 'Admin\ProfileController@updateDetails');
    Route::post('/api/admin/profile/password', 'Admin\ProfileController@passwordUpdate');
    
});
