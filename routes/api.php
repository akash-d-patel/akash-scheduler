<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\DataTransferObject\Arr;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'Api\LoginController@login');
Route::post('email/send', 'EmailHistoryController@send');
Route::post('sms/send', 'SmsHistoryController@send');

Route::post('send-email', function () {
    $details['email'] = 'jencysoftware@gmail.com';
    dispatch(new App\Jobs\TestEmailJob($details));
    return response()->json(['message' => 'Mail Send Successfully!!']);
});

//Route::post('register','Api\Customer\UserLoginController@registerwithotp');

Route::group(['middleware' => 'auth:api'], function () {

    /* Admin api */
    Route::resource('users', UserController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('email_templates', EmailTemplateController::class);
    Route::resource('email_histories', EmailHistoryController::class);
    Route::resource('email_attechment_histories', EmailAttechmentHistoryController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('sms_templates', SmsTemplateController::class);
    Route::resource('sms_histories', SmsHistoryController::class);
});
