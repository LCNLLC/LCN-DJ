<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jb_jobs';

    protected $fillable = [
        'name',
        'description',
        'content',
        'company_id',
        'address',
        'status',
        'apply_url',
        'is_freelance',
        'career_level_id',
        'salary_from',
        'salary_to',
        'salary_range',
        'currency_id',
        'degree_level_id',
        'job_shift_id',
        'job_experience_id',
        'functional_area_id',
        'hide_salary',
        'number_of_positions',
        'expire_date',
        'author_id',
        'author_type',
        'views',
        'number_of_applied',
        'hide_company',
        'latitude',
        'longitude',
        'auto_renew',
        'is_featured',
        'external_apply_clicks',
        'country_id',
        'state_id',
        'city_id',
        'employer_colleagues',
        'start_date',
        'application_closing_date',
        'cover_image'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function experience()
    {
        return $this->belongsTo(JobExperience::class, 'job_experience_id');
    }

    public function level()
    {
        return $this->belongsTo(JobCareerLevel::class, 'career_level_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id');
    }

    public function application()
    {
        return $this->hasMany(JobApplication::class);
    }

}
