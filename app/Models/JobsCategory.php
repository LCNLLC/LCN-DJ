<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobsCategory extends Model
{
    use HasFactory;
    protected $table = 'jb_jobs_categories';
    public $timestamps = false;

    protected $fillable = [
        'job_id',
        'category_id',
    ];

    public function SelectedjobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }
}
