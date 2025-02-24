<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class JobFunctionalArea extends Model
{
    use HasFactory;
    protected $table = 'jb_job_functional_areas';

    protected $fillable = [
        'name',
        'order',
        'is_default',
        'status',
    ];
   public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $job_functional_area_translations = $this->job_functional_area_translations->where('lang', $lang)->first();
      return $job_functional_area_translations != null ? $job_functional_area_translations->$field : $this->$field;
  }

  public function job_functional_area_translations(){
    return $this->hasMany(JobFunctionalAreaTranslation::class);
  }

}
