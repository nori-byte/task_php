<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    public $timestamps = false;
    protected $table = 'user_tokens';
    protected $fillable = ['user_id', 'token', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}