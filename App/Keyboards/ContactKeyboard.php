<?php


namespace Bot\App\Keyboards;


use BotFramework\App\Keyboards\ReplyKeyboardMarkup;
use BotFramework\App\Keyboards\ReplyKeyboardButton;
use BotFramework\Core\Supports\Traits\PropertyInjection;

class ContactKeyboard extends ReplyKeyboardMarkup
{
	use ReplyKeyboardButton;
	use PropertyInjection;

	private $name;
	private $lastname;

	protected function template ()
	{
		return [
			[$this->button($this->name), $this->button($this->lastname)]
		];
	}
}
