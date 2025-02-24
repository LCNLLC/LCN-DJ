<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCareerLevelTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_career_level_id'];

    public function JobCareerLevel(){
       return $this->belongsTo(JobCareerLevel::class);
    }
}
