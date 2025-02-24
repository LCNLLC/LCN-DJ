<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDegreeTypeTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_degree_type_id'];

    public function JobDegreeType(){
       return $this->belongsTo(JobDegreeType::class);
    }
}
