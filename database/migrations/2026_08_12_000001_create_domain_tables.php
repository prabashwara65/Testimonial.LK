<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainTables extends Migration
{
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('region')->unique();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->string('country')->unique();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('country_id');
            $table->string('province');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('province_id');
            $table->string('district');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        });

        Schema::create('vendor_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('address')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('br_no')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->date('renewal_start_date')->nullable();
            $table->decimal('renewal_charge', 12, 2)->nullable();
            $table->string('file_size')->nullable();
            $table->text('limit_regions')->nullable();
            $table->text('limit_countries')->nullable();
            $table->text('limit_provinces')->nullable();
            $table->text('limit_districts')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('emp_id')->nullable()->unique();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->string('nic')->nullable();
            $table->string('mobile')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->unsignedBigInteger('vendor_company_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id');
            $table->string('branch_code')->nullable();
            $table->string('name');
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('vendor_company_id')->references('id')->on('vendor_companies')->onDelete('cascade');
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id');
            $table->string('emp_id')->nullable();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('nic')->nullable();
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('incentive_cal')->nullable();
            $table->decimal('incentive_rate', 12, 2)->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_branch')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('branch_status')->default(1);
            $table->tinyInteger('company_status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('vendor_company_id')->references('id')->on('vendor_companies')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name');
            $table->string('emp_id')->nullable()->after('last_name');
            $table->string('username')->nullable()->after('emp_id');
            $table->string('nic')->nullable()->after('password');
            $table->string('mobile')->nullable()->after('nic');
            $table->unsignedBigInteger('region_id')->nullable()->after('mobile');
            $table->unsignedBigInteger('country_id')->nullable()->after('region_id');
            $table->string('otp_code')->nullable()->after('email');
            $table->string('address')->nullable()->after('otp_code');
            $table->string('address_line1')->nullable()->after('address');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('department')->nullable()->after('address_line2');
            $table->string('designation')->nullable()->after('department');
            $table->unsignedBigInteger('vendor_company_id')->nullable()->after('designation');
            $table->tinyInteger('status')->default(1)->after('vendor_company_id');
        });

        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'vendor_company_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->unsignedBigInteger('vendor_company_id')->nullable()->after('guard_name');
            });
        }

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('value')->nullable();
        });

        Schema::create('app_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        Schema::create('question_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id');
            $table->string('product_code')->nullable();
            $table->string('product_name');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('vendor_company_id')->references('id')->on('vendor_companies')->onDelete('cascade');
        });

        Schema::create('subproducts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('subproduct_code')->nullable();
            $table->string('subproduct_name');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id');
            $table->string('target_name');
            $table->tinyInteger('target_type')->nullable();
            $table->integer('target')->nullable();
            $table->integer('video')->nullable();
            $table->integer('audio')->nullable();
            $table->integer('image')->nullable();
            $table->integer('text')->nullable();
            $table->tinyInteger('video_type')->nullable();
            $table->tinyInteger('audio_type')->nullable();
            $table->tinyInteger('image_type')->nullable();
            $table->tinyInteger('text_type')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('vendor_company_id')->references('id')->on('vendor_companies')->onDelete('cascade');
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id');
            $table->string('campaign_name');
            $table->string('campaign_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('response_type')->nullable();
            $table->decimal('incentive_rate', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('vendor_company_id')->references('id')->on('vendor_companies')->onDelete('cascade');
        });

        Schema::create('campaign_has_branches', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('branch_id');
            $table->primary(['campaign_id', 'branch_id']);
        });

        Schema::create('campaign_has_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('vendor_id');
            $table->primary(['campaign_id', 'vendor_id']);
        });

        Schema::create('campaign_has_products', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('product_id');
            $table->primary(['campaign_id', 'product_id']);
        });

        Schema::create('campaign_has_subproducts', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('subproduct_id');
            $table->primary(['campaign_id', 'subproduct_id']);
        });

        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id');
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionnaire_id');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->text('question')->nullable();
            $table->string('required_needed')->nullable();
            $table->string('survey_question')->nullable();
            $table->string('sub_question')->nullable();
            $table->timestamps();
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires')->onDelete('cascade');
        });

        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->text('value')->nullable();
            $table->text('sub_questionnaire_question_id')->nullable();
            $table->timestamps();
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });

        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('subproduct_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('response_type')->nullable();
            $table->string('input_source')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('geo_address')->nullable();
            $table->string('status')->default('pending');
            $table->integer('rating')->nullable()->default(0);
            $table->text('reject_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('response_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->unsignedBigInteger('question_id')->nullable();
            $table->text('answer')->nullable();
            $table->timestamps();
            $table->foreign('response_id')->references('id')->on('responses')->onDelete('cascade');
        });

        Schema::create('response_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->string('image')->nullable();
            $table->text('text')->nullable();
            $table->timestamps();
            $table->foreign('response_id')->references('id')->on('responses')->onDelete('cascade');
        });

        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id');
            $table->date('date')->nullable();
            $table->string('reward_code')->nullable();
            $table->string('reward_type')->nullable();
            $table->string('discount')->nullable();
            $table->string('gift')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('user_has_rewards', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reward_id');
            $table->unsignedBigInteger('vendor_company_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->primary(['user_id', 'reward_id']);
        });

        Schema::create('incentives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->unsignedBigInteger('vendor_company_id')->nullable();
            $table->decimal('incentive_amount', 12, 2)->nullable();
            $table->date('paid_date')->nullable();
            $table->date('reject_date')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_company_id');
            $table->date('renewal_date')->nullable();
            $table->decimal('renewal_charge', 12, 2)->nullable();
            $table->date('paid_date')->nullable();
            $table->timestamps();
        });

        Schema::create('action_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user')->nullable();
            $table->unsignedBigInteger('vendor_company_id')->nullable();
            $table->string('ip')->nullable();
            $table->string('action')->nullable();
            $table->string('subject')->nullable();
            $table->longText('parameters')->nullable();
            $table->longText('response')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_logs');
        Schema::dropIfExists('payment_renewals');
        Schema::dropIfExists('incentives');
        Schema::dropIfExists('user_has_rewards');
        Schema::dropIfExists('rewards');
        Schema::dropIfExists('response_records');
        Schema::dropIfExists('response_questions');
        Schema::dropIfExists('responses');
        Schema::dropIfExists('question_answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('questionnaires');
        Schema::dropIfExists('campaign_has_subproducts');
        Schema::dropIfExists('campaign_has_products');
        Schema::dropIfExists('campaign_has_employees');
        Schema::dropIfExists('campaign_has_branches');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('targets');
        Schema::dropIfExists('subproducts');
        Schema::dropIfExists('products');
        Schema::dropIfExists('question_types');
        Schema::dropIfExists('app_versions');
        Schema::dropIfExists('settings');

        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'vendor_company_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('vendor_company_id');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'last_name', 'emp_id', 'username', 'nic', 'mobile', 'region_id', 'country_id',
                'otp_code', 'address', 'address_line1', 'address_line2', 'department',
                'designation', 'vendor_company_id', 'status',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('vendors');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('vendor_companies');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('regions');
    }
}
