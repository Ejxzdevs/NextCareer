<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',        // add this!
        'address',
        'skills',
        'occupation',
        'about',
        'profile_picture',
        'user_status',    // include if you want to set it later
    ];
    
    protected $casts = [
        'skills' => 'array',
    ];
    
      public function user()
    {
        return $this->belongsTo(User::class);
    }
}
