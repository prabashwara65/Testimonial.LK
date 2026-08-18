<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth:admin'], function(){
    Route::prefix('admin')->group(function() {
        // No permission redirect route
        Route::get('/permission-denied', function () {
            return view('admin::no-permission');
        })->name('admin.permission-denied');


        // Dashboard
        Route::get('/', 'DashboardController@index')->name('admin.dashboard');
        Route::post('/', 'DashboardController@index')->name('admin.dashboard.vendor-wise');


        // User routes
        Route::prefix('users')->name('admin.users')->group(function () {
            Route::get('get-data', 'UserController@getData')->name('.get-data');
            Route::post('change-status', 'UserController@changeStatus')->name('.status');
        });
        Route::resource('users', 'UserController', resourceNames('admin.users'));

        // Role routes
        Route::get('roles/get-data', 'RoleController@getData')->name('admin.roles.get-data');
        Route::resource('roles', 'RoleController', resourceNames('admin.roles'));

        // Permission routes
        Route::get('permissions/get-data', 'PermissionController@getData')->name('admin.permissions.get-data');
        Route::resource('permissions', 'PermissionController', resourceNames('admin.permissions'));

        // Action Log routes
        Route::prefix('action-log')->name('admin.action-log')->group(function () {
            Route::get('/', 'ActionLogController@index');
            Route::get('get-data', 'ActionLogController@getData')->name('.get-data');
            Route::get('show/{id}', 'ActionLogController@show')->name('.show');
            Route::put('update/{id}', 'ActionLogController@update')->name('.update');
        });

        // Vendor routes
        Route::prefix('vendors')->name('admin.vendors')->group(function () {
            Route::get('get-data', 'VendorController@getData')->name('.get-data');
            Route::post('change-status', 'VendorController@changeStatus')->name('.status');
        });
        Route::resource('vendors', 'VendorController', resourceNames('admin.vendors'));

        // Vendor company routes
        Route::prefix('vendor-companies')->name('admin.vendor-companies')->group(function () {
            Route::get('get-data', 'VendorCompanyController@getData')->name('.get-data');
            Route::post('change-status', 'VendorCompanyController@changeStatus')->name('.status');
        });
        Route::resource('vendor-companies', 'VendorCompanyController', resourceNames('admin.vendor-companies'));

        // Payment renewal routes
        Route::prefix('payment-renewals')->name('admin.payment-renewals')->group(function () {
            Route::get('paid', 'PaymentRenewalController@paid')->name('.paid');
            Route::get('paid/get-data', 'PaymentRenewalController@getData')->name('.paid.get-data');

            Route::get('pending', 'PaymentRenewalController@pending')->name('.pending');
            Route::get('pending/get-data', 'PaymentRenewalController@getData')->name('.pending.get-data');
        });
        Route::resource('payment-renewals', 'PaymentRenewalController', resourceNames('admin.payment-renewals'));

        // Testimonials routes
        Route::prefix('testimonials')->name('admin.testimonials')->group(function () {
            Route::get('approved', 'TestimonialFeedbackController@approved')->name('.approved');
            Route::get('approved/get-data', 'TestimonialFeedbackController@getData')->name('.approved.get-data');

            Route::get('reject', 'TestimonialFeedbackController@reject')->name('.reject');
            Route::get('reject/get-data', 'TestimonialFeedbackController@getData')->name('.reject.get-data');

            Route::get('pending', 'TestimonialFeedbackController@pending')->name('.pending');
            Route::get('pending/get-data', 'TestimonialFeedbackController@getData')->name('.pending.get-data');
        });

        // Feedbacks routes
        Route::prefix('feedbacks')->name('admin.feedbacks')->group(function () {
            Route::get('approved', 'TestimonialFeedbackController@approved')->name('.approved');
            Route::get('approved/get-data', 'TestimonialFeedbackController@getData')->name('.approved.get-data');

            Route::get('reject', 'TestimonialFeedbackController@reject')->name('.reject');
            Route::get('reject/get-data', 'TestimonialFeedbackController@getData')->name('.reject.get-data');

            Route::get('pending', 'TestimonialFeedbackController@pending')->name('.pending');
            Route::get('pending/get-data', 'TestimonialFeedbackController@getData')->name('.pending.get-data');
        });

        // Testimonials and Feedbacks same routes
        Route::prefix('testimonial-feedback')->name('admin.testimonial-feedback')->group(function () {
            Route::get('questionnaire/show/{id}', 'TestimonialFeedbackController@showQuestionnaire')->name('.questionnaire.show');
            Route::get('record/show/{id}', 'TestimonialFeedbackController@showRecord')->name('.record.show');
        });
        Route::resource('testimonial-feedback', 'TestimonialFeedbackController', resourceNames('admin.testimonial-feedback'));

        // Customer routes
        Route::prefix('customers')->name('admin.customers')->group(function () {
            Route::get('get-data', 'CustomerController@getData')->name('.get-data');
            Route::post('change-status', 'CustomerController@changeStatus')->name('.status');
        });
        Route::resource('customers', 'CustomerController', resourceNames('admin.customers'));

        // Region routes
        Route::prefix('regions')->name('admin.regions')->group(function () {
            Route::get('get-data', 'RegionController@getData')->name('.get-data');
        });
        Route::resource('regions', 'RegionController', resourceNames('admin.regions'));

        // Country routes
        Route::prefix('countries')->name('admin.countries')->group(function () {
            Route::get('get-data', 'CountryController@getData')->name('.get-data');
        });
        Route::resource('countries', 'CountryController', resourceNames('admin.countries'));

        // Province routes
        Route::prefix('provinces')->name('admin.provinces')->group(function () {
            Route::get('get-data', 'ProvinceController@getData')->name('.get-data');
        });
        Route::resource('provinces', 'ProvinceController', resourceNames('admin.provinces'));

        // District routes
        Route::prefix('districts')->name('admin.districts')->group(function () {
            Route::get('get-data', 'DistrictController@getData')->name('.get-data');
        });
        Route::resource('districts', 'DistrictController', resourceNames('admin.districts'));

        // Settings routes
        Route::resource('settings', 'SettingController', resourceNames('admin.settings'));

        ///////////////
        /// REPORTS ///
        ///////////////

        // Total Summary report routes
        Route::prefix('total-summary-report')->name('admin.total-summary-report')->group(function () {
            Route::get('/', 'TotalSummaryReportController@index');
            Route::get('get-data', 'TotalSummaryReportController@getData')->name('.get-data');
        });

        // Product report routes
        Route::prefix('product-report')->name('admin.product-report')->group(function () {
            Route::get('/', 'ProductReportController@index');
            Route::get('get-data', 'ProductReportController@getData')->name('.get-data');
        });
    });
});
