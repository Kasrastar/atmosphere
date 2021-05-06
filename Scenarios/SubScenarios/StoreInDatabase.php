<?php


namespace Bot\Scenarios\SubScenarios;


use Bot\Models\Message;
use Longman\TelegramBot\Entities\Update;
use BotFramework\Scenarios\SubScenarios\SubScenario;

class StoreInDatabase extends SubScenario
{
	protected function handle (Update $update)
	{
		$text = $update->getMessage()->getText();
		$from = $update->getMessage()->getFrom()->getUsername();

		Message::create([
			'message' => $text,
			'from' => $from
		]);
	}
}
