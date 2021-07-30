<?php

namespace App\Models;

use Atmosphere\Database\Model\Model;
use Atmosphere\Database\Model\CurrentRoute;

class User extends Model
{
	protected $guarded = ['id'];

	public function current_route ()
	{
		return $this->belongsTo(CurrentRoute::class, 'current_route_id', 'id');
	}
}
