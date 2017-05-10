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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
