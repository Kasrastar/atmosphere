<?php


namespace Bot\App\Keyboards;


use BotFramework\App\Keyboards\ReplyKeyboardMarkup;
use BotFramework\App\Keyboards\ReplyKeyboardButton;

class ExampleReplyKeyboard extends ReplyKeyboardMarkup
{
	use ReplyKeyboardButton;

	/**
	 * @return array
	 */
	public function template ()
	{
		return [
			[$this->button('sda'), $this->button('fff')],
			[$this->button('zaaaaaaaaaaart')]
		];
	}
}
