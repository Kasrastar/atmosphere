<?php


namespace Bot\Providers;


class ScenarioProvider
{
	public static function register () : array
	{
		return [
			\Bot\Scenarios\AnswerHello::class,
			\Bot\Scenarios\ForwardSpecialMessageToTheChannel::class,
		];
	}
}
