<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSkillTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_skill_id'];

    public function JobSkill(){
       return $this->belongsTo(JobSkill::class);
    }
}
