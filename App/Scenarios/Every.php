<?php


namespace Bot\App\Scenarios;


use BotFramework\App\Scenarios\Scenario;
use Longman\TelegramBot\Entities\Update;
use Bot\App\Keyboards\ExampleReplyKeyboard;

class Every extends Scenario
{
	protected $conditions = [
		// condition classes here
	];

	protected function handle (Update $update)
	{
		response()->keyboard(new ExampleReplyKeyboard())->send('done!');
//		response()->send('done!');
	}
}
