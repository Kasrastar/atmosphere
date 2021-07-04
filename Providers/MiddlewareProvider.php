<?php

namespace Bot\Providers;


class MiddlewareProvider
{
	public static function register()
	{
		return [
			\Bot\App\Middlewares\CheckFosh::class,
		];
	}
}
