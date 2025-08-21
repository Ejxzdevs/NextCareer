<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'address',
        'skills',
        'occupation',
        'about',
    ];
    protected $casts = [
        'skills' => 'array',
    ];
      public function user()
    {
        return $this->belongsTo(User::class);
    }
}
