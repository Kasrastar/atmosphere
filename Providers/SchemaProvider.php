<?php


namespace Bot\Providers;


class SchemaProvider
{
	public static function register ()
	{
		return [
			\Bot\Database\Schemas\MessageSchema::class,
		];
	}
}