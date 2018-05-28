<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WallComment extends Model
{
	protected $table = 'wall_post_comments';
	protected $primaryKey = 'comment_id';


	protected $dates = ['deleted_at'];
}
