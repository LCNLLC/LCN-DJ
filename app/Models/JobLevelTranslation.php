<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLevelTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_level_id'];

    public function JobLevel(){
       return $this->belongsTo(JobShift::class);
    }
}
