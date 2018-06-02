<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class WallComment extends Model
{	
	use SoftDeletes;

	protected $table = 'wall_post_comments';
	protected $primaryKey = 'comment_id';


	protected $dates = ['deleted_at'];
}
