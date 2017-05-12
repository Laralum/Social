<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laralum\Social\Models\Settings;

class CreateLaralumSocialSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laralum_social_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enabled');
            $table->boolean('allow_register');
            $table->text('facebook_client_id')->nullable();
            $table->text('facebook_client_secret')->nullable();
            $table->text('twitter_client_id')->nullable();
            $table->text('twitter_client_secret')->nullable();
            $table->text('linkedin_client_id')->nullable();
            $table->text('linkedin_client_secret')->nullable();
            $table->text('google_client_id')->nullable();
            $table->text('google_client_secret')->nullable();
            $table->text('github_client_id')->nullable();
            $table->text('github_client_secret')->nullable();
            $table->text('bitbucket_client_id')->nullable();
            $table->text('bitbucket_client_secret')->nullable();
            $table->timestamps();
        });

        Settings::create([
            'enabled'        => false,
            'allow_register' => false,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laralum_social_settings');
    }
}
