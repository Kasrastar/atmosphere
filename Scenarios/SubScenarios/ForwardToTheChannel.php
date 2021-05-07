<?php


namespace Bot\Scenarios\SubScenarios;


use Longman\TelegramBot\Entities\Update;
use BotFramework\Scenarios\SubScenarios\SubScenario;
use Bot\TelegramCommunications\Channels\PialeChannel;

class ForwardToTheChannel extends SubScenario
{
	protected function handle (Update $update)
	{
		$channel = new PialeChannel();

		$channel->createPost('lol');
	}
}
