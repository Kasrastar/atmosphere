<?php


namespace Bot\App\Scenarios\Conditions;


use BotFramework\App\Scenarios\Conditions\Condition;
use Longman\TelegramBot\Entities\Update;

class IsCallbackQuery extends Condition
{
	public function check (Update $update) : bool
	{
		return isset($update['callback_query']);
	}
}
