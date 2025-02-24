<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class JobSkill extends Model
{
    use HasFactory;
    protected $table = 'jb_job_skills';

    protected $fillable = [
        'name',
        'order',
        'is_default',
        'status',
    ];
   public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $job_skill_translations = $this->job_skill_translations->where('lang', $lang)->first();
      return $job_skill_translations != null ? $job_skill_translations->$field : $this->$field;
  }

  public function job_skill_translations(){
    return $this->hasMany(JobSkillTranslation::class);
  }

}
