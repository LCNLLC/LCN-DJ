<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategoryTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_category_id'];

    public function JobCategory(){
       return $this->belongsTo(JobCategory::class);
    }
}
