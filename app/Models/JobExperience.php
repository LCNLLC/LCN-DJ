<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class JobExperience extends Model
{
    use HasFactory;
    protected $table = 'jb_job_experiences';

    protected $fillable = [
        'name',
        'order',
        'is_default',
        'status',
    ];
   public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $job_experience_translations = $this->job_experience_translations->where('lang', $lang)->first();
      return $job_experience_translations != null ? $job_experience_translations->$field : $this->$field;
  }

  public function job_experience_translations(){
    return $this->hasMany(JobExperienceTranslation::class);
  }

}
