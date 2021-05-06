<?php


namespace Bot\Scenarios\Conditions;


use BotFramework\Scenarios\Conditions\Condition;

class IsNotCommand extends Condition
{
	public function check (\Longman\TelegramBot\Entities\Update $update) : bool 
	{
		return $update->getMessage()->getText()[0] != '/';
	}
}
