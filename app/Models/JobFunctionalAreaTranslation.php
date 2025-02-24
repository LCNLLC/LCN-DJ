<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFunctionalAreaTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_functionl_area_id'];

    public function JobFunctionalArea(){
       return $this->belongsTo(JobFunctionalArea::class);
    }
}
