<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class JobCareerLevel extends Model
{
    use HasFactory;
    protected $table = 'jb_job_career_levels';

    protected $fillable = [
        'name',
        'order',
        'is_default',
        'status',
    ];
   public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $job_career_level_translations = $this->job_career_level_translations->where('lang', $lang)->first();
      return $job_career_level_translations != null ? $job_career_level_translations->$field : $this->$field;
  }

  public function job_career_level_translations(){
    return $this->hasMany(JobCareerLevelTranslation::class);
  }

}
