<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobsSkill extends Model
{
    use HasFactory;
    protected $table = 'jb_jobs_skills';
    public $timestamps = false;

    protected $fillable = [
        'job_id',
        'job_skill_id',
    ];
    public function SelectedjobSkill()
    {
        return $this->belongsTo(JobSkill::class, 'job_skill_id');
    }
}
