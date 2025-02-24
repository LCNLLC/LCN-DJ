<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class JobType extends Model
{
    use HasFactory;
    protected $table = 'jb_job_types';

    protected $fillable = [
        'name',
        'order',
        'is_default',
        'status',
    ];
   public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $job_type_translations = $this->job_type_translations->where('lang', $lang)->first();
      return $job_type_translations != null ? $job_type_translations->$field : $this->$field;
  }

  public function job_type_translations(){
    return $this->hasMany(JobTypeTranslation::class);
  }


}
