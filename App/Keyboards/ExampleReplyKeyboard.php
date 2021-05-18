<?php


namespace Bot\App\Keyboards;


use BotFramework\App\Keyboards\ReplyKeyboardMarkup;
use BotFramework\App\Keyboards\ReplyKeyboardButton;

class ExampleReplyKeyboard extends ReplyKeyboardMarkup
{
	use ReplyKeyboardButton;

	public function template ()
	{
		return [
			[$this->add('sda'), $this->add('fff')],
			[$this->add('zaaaaaaaaaaart')]
		];
	}
}
