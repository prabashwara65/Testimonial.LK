<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ResponseController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/videoRecord', function () {
    return view('videoRecord');
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});

Route::get('/clear-cache', function () {
    Artisan::call('event:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/privacy', function () {
    return view('privacy');
});


Route::post('load-countries', 'LocationController@loadCountries')->name('load-countries');
Route::post('load-provinces', 'LocationController@loadProvinces')->name('load-provinces');
Route::post('load-districts', 'LocationController@loadDistricts')->name('load-districts');

Route::group(['middleware' => 'auth:web'], function () {
    Route::get('/profile', [CustomerController::class, 'edit'])->name('profile');
    Route::post('/profile/update', [CustomerController::class, 'update'])->name('profile.update');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/get-data', [HistoryController::class, 'getData'])->name('history.get-data');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');

    Route::prefix('customer-response')->name('customer-response')->group(function () {
        Route::get('/step-one', [ResponseController::class, 'stepOne'])->name('.step-one');
        Route::post('/step-one', [ResponseController::class, 'postStepOne'])->name('.step-one.post');

        Route::get('/step-two', [ResponseController::class, 'stepTwo'])->name('.step-two');
        Route::post('/step-two', [ResponseController::class, 'postStepTwo'])->name('.step-two.post');

        Route::get('/video-record', [ResponseController::class, 'videoRecord'])->name('.video-record');
        Route::get('/audio-record', [ResponseController::class, 'audioRecord'])->name('.audio-record');
        Route::get('/image-record', [ResponseController::class, 'imageRecord'])->name('.image-record');

        Route::post('/load-branches', [ResponseController::class, 'loadBranches'])->name('.load-branches');
        Route::post('/load-products', [ResponseController::class, 'loadProducts'])->name('.load-products');
        Route::post('/load-subproducts', [ResponseController::class, 'loadSubproducts'])->name('.load-subproducts');
    });
});

Route::prefix('admin')->group(function () {
    Route::get('login', [App\Http\Controllers\AuthAdmin\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [App\Http\Controllers\AuthAdmin\LoginController::class, 'login']);
    Route::get('password/confirm', [App\Http\Controllers\AuthAdmin\ConfirmPasswordController::class, 'showConfirmForm'])->name('admin.password.confirm');
    Route::post('password/confirm', [App\Http\Controllers\AuthAdmin\ConfirmPasswordController::class, 'confirm']);
    Route::post('password/email', [App\Http\Controllers\AuthAdmin\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('password/reset', [App\Http\Controllers\AuthAdmin\ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('password/reset', [App\Http\Controllers\AuthAdmin\ResetPasswordController::class, 'reset'])->name('admin.password.update');
    Route::get('password/reset/{token}', [App\Http\Controllers\AuthAdmin\ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
});

Route::prefix('vendor')->group(function () {
    Route::get('login', [App\Http\Controllers\AuthVendor\LoginController::class, 'showLoginForm'])->name('vendor.login');
    Route::post('login', [App\Http\Controllers\AuthVendor\LoginController::class, 'login']);
    Route::get('password/confirm', [App\Http\Controllers\AuthVendor\ConfirmPasswordController::class, 'showConfirmForm'])->name('vendor.password.confirm');
    Route::post('password/confirm', [App\Http\Controllers\AuthVendor\ConfirmPasswordController::class, 'confirm']);
    Route::post('password/email', [App\Http\Controllers\AuthVendor\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('vendor.password.email');
    Route::get('password/reset', [App\Http\Controllers\AuthVendor\ForgotPasswordController::class, 'showLinkRequestForm'])->name('vendor.password.request');
    Route::post('password/reset', [App\Http\Controllers\AuthVendor\ResetPasswordController::class, 'reset'])->name('vendor.password.update');
    Route::get('password/reset/{token}', [App\Http\Controllers\AuthVendor\ResetPasswordController::class, 'showResetForm'])->name('vendor.password.reset');
});
