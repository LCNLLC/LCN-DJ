<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProfile extends Model
{
    use HasFactory;

    protected $table = 'jb_job_profiles';

    protected $fillable = [
    	'user_id',
    	'categories',
    	'skills',
    	'types',
    	'job_shift_id',
        'career_level_id',
        'degree_level_id',
        'functional_area_id',
        'job_experience_id',
        'nationality',
        'gender',
        'dob',
        'age',
   
    ];

       protected $casts = [
        'categories' => 'json',
        'skills' => 'json',
        'types' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function experience()
    {
        return $this->belongsTo(JobExperience::class, 'job_experience_id');
    }

    public function level()
    {
        return $this->belongsTo(JobCareerLevel::class, 'career_level_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'nationality');
    }

}
