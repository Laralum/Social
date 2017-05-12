<?php

namespace Laralum\Social\Models;

use Illuminate\Database\Eloquent\Model;
use Laralum\Users\Models\User;

class Social extends Model
{
    public $table = 'laralum_social';
    public $fillable = [
        'user_id', 'provider', 'token', 'secret_token',
    ];

    /**
     * Return the user from the social account.
     *
     * @return \Laralum\Users\Moodels\User;
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return all the providers supported.
     *
     * @return array
     */
    public static function providers()
    {
        return collect(Settings::first()->fillable)->filter(function ($value) {
            return strpos($value, '_client_id') !== false;
        })->map(function ($value) {
            return str_replace('_client_id', '', $value);
        })->toArray();
    }

    /**
     * Returns all the available providers.
     *
     * @return array
     */
    public static function availableProviders()
    {
        $settings = Settings::first();

        return collect(static::providers())->filter(function ($value) use ($settings) {
            $ci = $value.'_client_id';
            $cs = $value.'_client_secret';

            return $settings->$ci && $settings->$cs;
        })->toArray();
    }
}
