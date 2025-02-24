<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobShiftTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lang', 'job_shift_id'];

    public function JobShift(){
       return $this->belongsTo(JobShift::class);
    }
}
