<?php


namespace Bot\Scenarios\Conditions;


use BotFramework\Scenarios\Conditions\Condition;

class DoesHaveChannelSharp extends Condition
{
	public function check (\Longman\TelegramBot\Entities\Update $update) : bool 
	{
		return strpos($update->getMessage()->getText(), '#channel') !== false;
	}
}
