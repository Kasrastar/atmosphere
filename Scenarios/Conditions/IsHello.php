<?php


namespace Bot\Scenarios\Conditions;


use BotFramework\Scenarios\Conditions\Condition;

class IsHello extends Condition
{
	public function check (\Longman\TelegramBot\Entities\Update $update) : bool 
	{
		return strtolower($update->getMessage()->getText()) == 'hello';
	}
}
