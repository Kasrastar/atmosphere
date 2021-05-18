<?php


namespace Bot\App\Views;


use BotFramework\App\Views\View;
use BotFramework\App\Views\Designer\Text;
use BotFramework\App\Views\Designer\Designer;
use BotFramework\Core\Supports\Traits\PropertyInjection;

class PialeChannelView extends View
{
	use PropertyInjection;

	private $text;

	protected function template (Designer $designer)
	{
		$designer->add(new Text([
			'🔸🔻🔹🔸🔻🔹🔸🔻🔹',
			'',
			$this->text,
			'',
			'🔸🔻🔹🔸🔻🔹🔸🔻🔹',
		]));
	}
}
