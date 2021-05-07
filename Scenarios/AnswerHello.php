<?php


namespace Bot\Scenarios;


use Bot\Views\ExampleView;
use BotFramework\Scenarios\Scenario;
use Longman\TelegramBot\Entities\Update;

class AnswerHello extends Scenario
{
	protected $conditions = [
		\Bot\Scenarios\Conditions\IsNotCommand::class,
//		\Bot\Scenarios\Conditions\IsHello::class,
	];

	protected function handle (Update $update)
	{
		$view = new ExampleView('C:\\Users\\PialeChini\\Desktop\\2.png', 'Oops!');
		response()->send($view);
	}
}
