<?php


namespace Bot\Scenarios;


use BotFramework\Scenarios\Scenario;

class ForwardSpecialMessageToTheChannel extends Scenario
{
	protected $conditions = [
		\Bot\Scenarios\Conditions\IsNotCommand::class,
		\Bot\Scenarios\Conditions\DoesHaveChannelSharp::class,
	];

	protected $subScenarios = [
		\Bot\Scenarios\SubScenarios\ForwardToTheChannel::class,
		\Bot\Scenarios\SubScenarios\StoreInDatabase::class,
	];
}
