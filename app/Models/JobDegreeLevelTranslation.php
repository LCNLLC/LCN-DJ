<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDegreeLevelTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_degree_level_id'];

    public function JobDegreeLevel(){
       return $this->belongsTo(JobDegreeLevel::class);
    }
}
