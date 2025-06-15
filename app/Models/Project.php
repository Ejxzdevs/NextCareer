<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Define which columns are mass-assignable
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'skills',
        'budget',
        'start_date',
        'deadline',
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class); // A project belongs to one user
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

}

