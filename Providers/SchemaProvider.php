<?php

namespace Bot\Providers;


class SchemaProvider
{
	/**
	 * @return string[]
	 */
	public static function register()
	{
		return [
			\Bot\Database\Schemas\MessageSchema::class,
		];
	}
}
