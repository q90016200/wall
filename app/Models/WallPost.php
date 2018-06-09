<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class WallPost extends Model
{
    use SoftDeletes;
    protected $table = 'wall_posts';
	protected $primaryKey = 'post_id';

	protected $dates = ['deleted_at'];

}
