<?php


namespace Bot\Providers;


class ScenarioProvider
{
	/**
	 * @return array[]
	 */
	public static function register ()
	{
		return [
			'Scenarios' => [
				\Bot\App\Scenarios\Every::class,
			],
			'CallbackQueryScenarios' => [

			]
		];
	}
}
