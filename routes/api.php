<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ForgotPasswordController;
use App\Http\Controllers\Api\v1\DashboardController;
use App\Http\Controllers\Api\v1\CustomerController;
use App\Http\Controllers\Api\v1\ResponseController;
use App\Http\Controllers\Api\v1\TestimonialFeedbackController;

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

Route::prefix('v1')->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgotPassword', [ForgotPasswordController::class, 'sendResetLinkEmail']);

    Route::group(['middleware' => ['auth:sanctum']], function(){
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('dashboard', [DashboardController::class, 'dashboard']);
        Route::get('dashboard/getEarning', [DashboardController::class, 'getEarning']);

        Route::prefix('customer')->group(function(){
            Route::get('getRegion', [CustomerController::class, 'getRegion']);
            Route::get('getCountry', [CustomerController::class, 'getCountry']);

            Route::get('getCustomer', [CustomerController::class, 'getCustomer']);
            Route::post('createCustomer', [CustomerController::class, 'createCustomer']);
            Route::post('otpVerify', [CustomerController::class, 'otpVerify']);
            Route::post('otpResend', [CustomerController::class, 'otpResend']);
        });

        Route::prefix('response')->group(function(){
            Route::get('getBranch', [ResponseController::class, 'getBranch']);
            Route::get('getProduct', [ResponseController::class, 'getProduct']);
            Route::get('getSubproduct', [ResponseController::class, 'getSubproduct']);
            Route::get('getCampaign', [ResponseController::class, 'getCampaign']);

            Route::post('createResponse', [ResponseController::class, 'createResponse']);
        });

        Route::prefix('testimonialFeedback')->group(function(){
            Route::get('getAllCampaigns', [TestimonialFeedbackController::class, 'getAllCampaigns']);
            Route::get('getResponsesList', [TestimonialFeedbackController::class, 'getResponsesList']);
            Route::get('getResponse', [TestimonialFeedbackController::class, 'getResponse']);
        });
    });
});
