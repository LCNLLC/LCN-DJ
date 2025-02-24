<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class JobShift extends Model
{
    use HasFactory;
    protected $table = 'jb_job_shifts';

    protected $fillable = [
        'name',
        'order',
        'is_default',
        'status',
    ];
   public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $job_shift_translations = $this->job_shift_translations->where('lang', $lang)->first();
      return $job_shift_translations != null ? $job_shift_translations->$field : $this->$field;
  }

  public function job_shift_translations(){
    return $this->hasMany(JobShiftTranslation::class);
  }

}
