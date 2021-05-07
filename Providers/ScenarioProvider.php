<?php


namespace Bot\Providers;


class ScenarioProvider
{
	public static function register () : array
	{
		return [
			\Bot\Scenarios\ForwardSpecialMessageToTheChannel::class,
			\Bot\Scenarios\AnswerHello::class,
		];
	}
}
