<?php


namespace Bot;


use Bot\Providers\SchemaProvider;
use Bot\Providers\ScenarioProvider;
use Bot\Providers\MiddlewareProvider;

class Application
{
	public static function getMiddlewares ()
	{
		return MiddlewareProvider::register();
	}

	public static function getScenarios ()
	{
		return ScenarioProvider::register();
	}

	public static function getSchemas ()
	{
		return SchemaProvider::register();
	}

	public static function getCurrentDir ()
	{
		return __DIR__;
	}
}
