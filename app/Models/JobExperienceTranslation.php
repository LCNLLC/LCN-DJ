<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobExperienceTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_experience_id'];

    public function JobExperience(){
       return $this->belongsTo(JobExperience::class);
    }
}
