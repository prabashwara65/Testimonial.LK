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

Route::group(['middleware' => ['auth:vendor']], function(){
    Route::prefix('vendor')->group(function() {

        Route::group(['middleware' => ['role:Representative']], function () {
            Route::get('/', 'SalesRepDashboardController@index')->name('vendor.salesrep.dashboard');
            Route::post('/', 'SalesRepDashboardController@index')->name('vendor.salesrep.dashboard.filters');

            Route::prefix('response')->name('response')->group(function() {
                Route::get('/step-one', 'ResponseController@stepOne')->name('.step-one');
                Route::post('/step-one', 'ResponseController@postStepOne')->name('.step-one.post');

                Route::get('/step-two', 'ResponseController@stepTwo')->name('.step-two');
                Route::post('/step-two', 'ResponseController@postStepTwo')->name('.step-two.post');

                Route::get('/step-three', 'ResponseController@stepThree')->name('.step-three');
                Route::post('/step-three', 'ResponseController@postStepThree')->name('.step-three.post');

                Route::get('/step-four', 'ResponseController@stepFour')->name('.step-four');
                Route::post('/step-four', 'ResponseController@postStepFour')->name('.step-four.post');
                Route::post('/load-subproducts', 'ResponseController@loadSubproducts')->name('.load-subproducts');
                Route::post('/load-campaigns', 'ResponseController@loadCampaigns')->name('.load-campaigns');

                Route::get('/step-five', 'ResponseController@stepFive')->name('.step-five');
                Route::post('/step-five', 'ResponseController@postStepFive')->name('.step-five.post');
                Route::get('/video-record', 'ResponseController@videoRecord')->name('.video-record');
                Route::get('/audio-record', 'ResponseController@audioRecord')->name('.audio-record');
                Route::get('/image-record', 'ResponseController@imageRecord')->name('.image-record');
            });

            Route::get('/collection', 'CollectionController@index')->name('collection');
            Route::get('/collection/get-data', 'CollectionController@getData')->name('collection.get-data');
            Route::get('/collection/{id}', 'CollectionController@show')->name('collection.show');
        });

        Route::group(['middleware' => ['permission:Dashboard']], function () {
            Route::prefix('admin')->group(function() {
                // No permission redirect route
                Route::get('/permission-denied', function () {
                    return view('vendor::no-permission');
                })->name('vendor.permission-denied');

                // Dashboard
                Route::get('/', 'DashboardController@index')->name('vendor.dashboard');
                Route::post('/', 'DashboardController@index')->name('vendor.dashboard.map');
                Route::get('/show/{id}', 'DashboardController@show')->name('vendor.dashboard.show');


                // User routes
                Route::prefix('users')->name('vendor.users')->group(function () {
                    Route::get('get-data', 'UserController@getData')->name('.get-data');
                    Route::post('change-status', 'UserController@changeStatus')->name('.status');
                    Route::get('export/{type?}/{search_text?}', 'UserController@export')->name('.export');
                });
                Route::resource('users', 'UserController', resourceNames('vendor.users'));


                // Role routes
                Route::get('roles/get-data', 'RoleController@getData')->name('vendor.roles.get-data');
                Route::resource('roles', 'RoleController', resourceNames('vendor.roles'));


                // Action Log routes
                Route::prefix('action-log')->name('vendor.action-log')->group(function () {
                    Route::get('/', 'ActionLogController@index');
                    Route::get('get-data', 'ActionLogController@getData')->name('.get-data');
                    Route::get('show/{id}', 'ActionLogController@show')->name('.show');
                    Route::put('update/{id}', 'ActionLogController@update')->name('.update');
                });


                // Company routes
                Route::resource('company', 'CompanyController', resourceNames('vendor.company'));


                // Branch routes
                Route::prefix('branches')->name('vendor.branches')->group(function () {
                    Route::get('get-data', 'BranchController@getData')->name('.get-data');
                    Route::post('change-status', 'BranchController@changeStatus')->name('.status');
                    Route::get('export/{type?}/{search_text?}', 'BranchController@export')->name('.export');

                    Route::post('load-limit-countries', 'BranchController@loadLimitCountries')->name('.load-limit-countries');
                    Route::post('load-limit-provinces', 'BranchController@loadLimitProvinces')->name('.load-limit-provinces');
                    Route::post('load-limit-districts', 'BranchController@loadLimitDistricts')->name('.load-limit-districts');
                });
                Route::resource('branches', 'BranchController', resourceNames('vendor.branches'));


                // Product routes
                Route::prefix('products')->name('vendor.products')->group(function () {
                    Route::get('get-data', 'ProductController@getData')->name('.get-data');
                    Route::post('change-status', 'ProductController@changeStatus')->name('.status');
                    Route::get('export/{type?}/{search_text?}', 'ProductController@export')->name('.export');
                });
                Route::resource('products', 'ProductController', resourceNames('vendor.products'));


                // Subproduct routes
                Route::prefix('subproducts')->name('vendor.subproducts')->group(function () {
                    Route::get('get-data', 'SubproductController@getData')->name('.get-data');
                    Route::post('change-status', 'SubproductController@changeStatus')->name('.status');
                    Route::get('export/{type?}/{search_text?}', 'SubproductController@export')->name('.export');
                });
                Route::resource('subproducts', 'SubproductController', resourceNames('vendor.subproducts'));


                // Target routes
                Route::prefix('targets')->name('vendor.targets')->group(function () {
                    Route::get('get-data', 'TargetController@getData')->name('.get-data');
                    Route::post('change-status', 'TargetController@changeStatus')->name('.status');
                    Route::get('export/{type?}/{search_text?}', 'TargetController@export')->name('.export');
                });
                Route::resource('targets', 'TargetController', resourceNames('vendor.targets'));


                // Campaign routes
                Route::prefix('campaigns')->name('vendor.campaigns')->group(function () {
                    Route::get('get-data', 'CampaignController@getData')->name('.get-data');
                    Route::get('get-campaign-template/{count}', 'CampaignController@getCampaignTemplate')->name('.get-campaign-template');
                    Route::post('change-status', 'CampaignController@changeStatus')->name('.status');
                    Route::get('export/{type?}/{search_text?}', 'CampaignController@export')->name('.export');

                    Route::post('load-subproduct', 'CampaignController@loadSubproduct')->name('.load-subproduct');
                    Route::post('load-employee', 'CampaignController@loadEmployee')->name('.load-employee');
                });
                Route::resource('campaigns', 'CampaignController', resourceNames('vendor.campaigns'));


                // Questionnaires routes
                Route::prefix('questionnaires')->name('vendor.questionnaires')->group(function () {
                    Route::get('get-data', 'QuestionnaireController@getData')->name('.get-data');
                    Route::get('get-question-template/{count}', 'QuestionnaireController@getQuestionTemplate')->name('.get-question-template');
                    Route::post('change-status', 'QuestionnaireController@changeStatus')->name('.status');
                    Route::get('export/{type?}/{search_text?}', 'QuestionnaireController@export')->name('.export');
                });
                Route::resource('questionnaires', 'QuestionnaireController', resourceNames('vendor.questionnaires'));


                // Testimonials routes
                Route::prefix('testimonials')->name('vendor.testimonials')->group(function () {
                    Route::get('approved', 'TestimonialFeedbackController@approved')->name('.approved');
                    Route::get('approved/get-data', 'TestimonialFeedbackController@getData')->name('.approved.get-data');
                    Route::get('approved/export/{type?}/{search_text?}', 'TestimonialFeedbackController@export')->name('.approved.export');

                    Route::get('reject', 'TestimonialFeedbackController@reject')->name('.reject');
                    Route::get('reject/get-data', 'TestimonialFeedbackController@getData')->name('.reject.get-data');
                    Route::get('reject/export/{type?}/{search_text?}', 'TestimonialFeedbackController@export')->name('.reject.export');

                    Route::get('pending', 'TestimonialFeedbackController@pending')->name('.pending');
                    Route::get('pending/get-data', 'TestimonialFeedbackController@getData')->name('.pending.get-data');
                    Route::get('pending/export/{type?}/{search_text?}', 'TestimonialFeedbackController@export')->name('.pending.export');
                });

                // Feedbacks routes
                Route::prefix('feedbacks')->name('vendor.feedbacks')->group(function () {
                    Route::get('approved', 'TestimonialFeedbackController@approved')->name('.approved');
                    Route::get('approved/get-data', 'TestimonialFeedbackController@getData')->name('.approved.get-data');
                    Route::get('approved/export/{type?}/{search_text?}', 'TestimonialFeedbackController@export')->name('.approved.export');

                    Route::get('reject', 'TestimonialFeedbackController@reject')->name('.reject');
                    Route::get('reject/get-data', 'TestimonialFeedbackController@getData')->name('.reject.get-data');
                    Route::get('reject/export/{type?}/{search_text?}', 'TestimonialFeedbackController@export')->name('.reject.export');

                    Route::get('pending', 'TestimonialFeedbackController@pending')->name('.pending');
                    Route::get('pending/get-data', 'TestimonialFeedbackController@getData')->name('.pending.get-data');
                    Route::get('pending/export/{type?}/{search_text?}', 'TestimonialFeedbackController@export')->name('.pending.export');
                });

                // Testimonials and Feedbacks same routes
                Route::resource('testimonial-feedback', 'TestimonialFeedbackController', resourceNames('vendor.testimonial-feedback'));


                // Customer routes
                Route::prefix('customers')->name('vendor.customers')->group(function () {
                    Route::get('get-data', 'CustomerController@getData')->name('.get-data');
                    Route::get('export/{type?}/{search_text?}', 'CustomerController@export')->name('.export');

                    Route::get('testimonial/{id}', 'CustomerController@getTestimonial')->name('.testimonial');
                    Route::get('feedback/{id}', 'CustomerController@getFeedback')->name('.feedback');
                    Route::get('reward/{id}', 'CustomerController@getReward')->name('.reward');

                    Route::get('assignreward/{id}', 'CustomerController@assignReward')->name('.assignreward');
                    Route::post('storereward/{id}', 'CustomerController@storeReward')->name('.storereward');

                    Route::post('change-status', 'CustomerController@changeStatus')->name('.status');
                });
                Route::resource('customers', 'CustomerController', resourceNames('vendor.customers'));


                // Reward routes
                Route::prefix('rewards')->name('vendor.rewards')->group(function () {
                    Route::get('get-data', 'RewardController@getData')->name('.get-data');
                    Route::post('change-status', 'RewardController@changeStatus')->name('.status');
                    Route::get('export/{type?}/{search_text?}', 'RewardController@export')->name('.export');
                });
                Route::resource('rewards', 'RewardController', resourceNames('vendor.rewards'));


                // Incentives routes
                Route::prefix('incentives')->name('vendor.incentives')->group(function () {
                    Route::get('paid', 'IncentiveController@paid')->name('.paid');
                    Route::get('paid/get-data', 'IncentiveController@getData')->name('.paid.get-data');
                    Route::get('paid/export/{type?}/{search_text?}', 'IncentiveController@export')->name('.paid.export');

                    Route::get('reject', 'IncentiveController@reject')->name('.reject');
                    Route::get('reject/get-data', 'IncentiveController@getData')->name('.reject.get-data');
                    Route::get('reject/export/{type?}/{search_text?}', 'IncentiveController@export')->name('.reject.export');

                    Route::get('pending', 'IncentiveController@pending')->name('.pending');
                    Route::get('pending/get-data', 'IncentiveController@getData')->name('.pending.get-data');
                    Route::get('pending/export/{type?}/{search_text?}', 'IncentiveController@export')->name('.pending.export');
                });
                Route::resource('incentives', 'IncentiveController', resourceNames('vendor.incentives'));
            });
        });
    });
});
