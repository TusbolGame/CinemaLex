<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Footer details
Route::get('/v1/get/app/details', 'Users\HomeController@getAppDetails');


// Registers
Route::get('/v1/register/verify/{token}', 'Users\RegisterController@codeConfirmed')->name('email_confiem');
Route::post('/v1/create/register', 'Users\RegisterController@register');
Route::post('/v1/check/register/email', 'Users\RegisterController@sendForgetPassword');
Route::post('/v1/register/forget/checkhash', 'Users\RegisterController@forgetCheckHash');
Route::post('/v1/update/register/password', 'Users\RegisterController@recoverPassword');


Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {
   
    // Check user status
    Route::get('/get/check/user', 'Users\CheckStatusUserController@checkUserStatus');
    
    // User details 
    Route::get('/user', function(Request $request) {
        return response()->json(['name' => $request->user()->name, 'image' => $request->user()->image, 'email' => $request->user()->email, 'url'=> \Illuminate\Support\Facades\Storage::disk('s3')->url('users_image/')]);
    });

    // Discover 
    Route::get('/get/home', 'Users\HomeController@getHomeResult');

    // Get movies list or sort it by trening and genres 
    Route::get('/get/movies', 'Users\MoviesController@getAllMovies');
    Route::get('/get/movie/{id}', 'Users\MoviesController@getMovieDetails')->where('id', '^[\w-]*$');
    Route::post('/get/movies/sort', 'Users\MoviesController@sortMovies');

    // Get series list or sort it by trening and genres
    Route::get('/get/series', 'Users\SeriesController@getAllSeries');
    Route::get('/get/series/{id}', 'Users\SeriesController@getSeriesDetails')->where('id', '^[\w-]*$');
    Route::post('/get/series/sort', 'Users\SeriesController@sortSeries');

    // Get series and movies kids rating system 
    Route::get('/get/kids', 'Users\KidsController@getAll');

    // Tv
    Route::get('/get/tv', 'Users\TvController@getAll');

    // Create new collection or add to already collection 
    Route::get('/get/collection', 'Users\CollectionsController@getCollection');
    Route::get('/get/collection/{id}', 'Users\CollectionsController@getCollectionList')->where('id', '^[\w-]*$');
    Route::post('/create/item/collection', 'Users\CollectionsController@AddNewToCollection');
    Route::post('/update/collection', 'Users\CollectionsController@updateCollection');
    Route::post('/delete/collection', 'Users\CollectionsController@deleteCollection');
    Route::post('/delete/item/collection', 'Users\CollectionsController@DeleteFromCollection');

    // Add like to movie or delete it
    Route::post('/create/like', 'Users\LikesController@addLike');

    // Get movie and episode video,details -- Player
    Route::get('/get/watch/movie/{id}', 'Users\VideoPlayerController@getMovieVideo')->where('id', '^[\w-]*$');;
    Route::post('/get/watch/series', 'Users\VideoPlayerController@getEpisodeVideo');
    Route::get('/get/watch/tv/{id}', 'Users\TvController@getChannels')->where('id', '^[\w-]*$');;
    Route::post('/create/watch/movie/recently', 'Users\VideoPlayerController@setRecentlyTimeMovie');
    Route::post('/create/watch/series/recently', 'Users\VideoPlayerController@setRecentlyTimeEpiosde');
     
    // Reports
    Route::post('/create/report/movie', 'Users\VideoPlayerController@movieReport');
    Route::post('/create/report/series', 'Users\VideoPlayerController@seriesReport');

    // Get search by movie name or series, cast
    Route::post('/get/search', 'Users\SearchController@getSearch');

    // Get cast details
    Route::get('/get/cast/{id}', 'Users\CastController@getCastDetails')->where('id', '^[\w-]*$');

    // Profile
    Route::post('/update/profile/image', 'Users\ProfileController@avatarUpload');
    Route::post('/update/profile/details', 'Users\ProfileController@updateDetails');
    Route::post('/update/profile/password', 'Users\ProfileController@updatePassword');
    
    // Payment setting
    Route::get('/get/profile/payment', 'Users\ProfileController@getPaymentInfo');
    Route::get('/get/profile/payment/billing', 'Users\ProfileController@getBillingDetails');
    Route::get('/update/profile/payment/cancel_membership', 'Users\ProfileController@cancelMembership');
    Route::get('/update/profile/payment/resume_membership', 'Users\ProfileController@resumeMembership');
    Route::post('/update/profile/payment/update', 'Users\ProfileController@paymentUpdate');
    Route::post('/update/profile/payment/change_plan', 'Users\ProfileController@changePlan');
  
    // Update language
    Route::post('/update/profile/language', 'Users\ProfileController@changeLanguage');
  
    // Update caption style
    Route::post('/update/profile/caption', 'Users\ProfileController@changeCaption');
  
    // Get viewing history 
    Route::get('/get/profile/viewing_history', 'Users\ProfileController@getViewingHistory');

    // Mail confirm
    Route::get('/register/sendactivity', 'Users\RegisterController@sendActivityMail');
  
    // Payment
    Route::post('/register/payment', 'Users\RegisterController@payment');


});
