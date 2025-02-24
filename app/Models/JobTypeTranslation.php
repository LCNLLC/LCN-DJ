<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTypeTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_type_id'];

    public function JobType(){
       return $this->belongsTo(JobType::class);
    }
}
