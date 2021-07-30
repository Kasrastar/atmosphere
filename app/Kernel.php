<?php

namespace App;

use App\Middlewares\DoesHaveMe;
use Atmosphere\Contract\Kernel as KernelContract;

class Kernel implements KernelContract
{
	/**
	 * @return array
	 */
	public function middlewares ()
	{
		return [

		];
	}
	
	/**
	 * @return string[]
	 */
	public function globalMiddlewares ()
	{
		return [
		
		];
	}
	
	/**
	 * @return string[]
	 */
	public function schemas ()
	{
		return [
			\App\Schemas\UserSchema::class,
		];
	}
	
	/**
	 * @return string[]
	 */
	public function serviceProviders ()
	{
		return [
			\Atmosphere\Core\CoreServiceProvider::class,
			\Atmosphere\Gateway\TelegramApiServiceProvider::class,
			\Atmosphere\Database\DatabaseServiceProvider::class,
			\Atmosphere\Routing\RouteServiceProvider::class,
		];
	}
	
	/**
	 * @return string
	 */
	public function projectDir ()
	{
		return dirname(__DIR__);
	}
}
