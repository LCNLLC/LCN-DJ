<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobsType extends Model
{
    use HasFactory;
    protected $table = 'jb_jobs_types';
    public $timestamps = false;

    protected $fillable = [
        'job_id',
        'job_type_id',
    ];

    public function SelectedjobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }
}
