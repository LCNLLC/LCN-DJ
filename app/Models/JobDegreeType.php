<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class JobDegreeType extends Model
{
    use HasFactory;
    protected $table = 'jb_job_degree_types';

    protected $fillable = [
        'name',
        'job_degree_level_id',
        'order',
        'is_default',
        'status',
    ];
   public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $job_degree_type_translations = $this->job_degree_type_translations->where('lang', $lang)->first();
      return $job_degree_type_translations != null ? $job_degree_type_translations->$field : $this->$field;
  }

  public function job_degree_type_translations(){
    return $this->hasMany(JobDegreeTypeTranslation::class);
  }

    public function jobDegreeLevel()
    {
        return $this->belongsTo(JobDegreeLevel::class, 'job_degree_level_id');
    }

}
