<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class JobCategory extends Model
{
    use HasFactory;
    protected $table = 'jb_job_categories';

    protected $fillable = [
        'name',
        'order',
        'is_default',
        'status',
    ];
   public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $job_category_translations = $this->job_category_translations->where('lang', $lang)->first();
      return $job_category_translations != null ? $job_category_translations->$field : $this->$field;
  }

  public function job_category_translations(){
    return $this->hasMany(JobCategoryTranslation::class);
  }

}
