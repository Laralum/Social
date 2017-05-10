<?php

namespace Laralum\Social\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $table = 'laralum_social_settings';
    public $fillable = [
        'enabled', 'allow_register',
        'facebook_client_id', 'facebook_client_secret',
        'twitter_client_id', 'twitter_client_secret',
        'linkedin_client_id', 'linkedin_client_secret',
        'google_client_id', 'google_client_secret',
        'github_client_id', 'github_client_secret',
        'bitbucket_client_id', 'bitbucket_client_secret',
    ];
}
