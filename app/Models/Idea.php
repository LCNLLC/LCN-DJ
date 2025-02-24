<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
	public function user(){
		return $this->belongsTo(User::class);
	}

	public function ideareplies()
	{
		return $this->hasMany(IdeaReply::class)->orderBy('created_at', 'desc');
	}

}
?>
