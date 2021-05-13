<?php


namespace Bot\Views;


use BotFramework\App\Views\View;
use BotFramework\App\Views\Designer;
use BotFramework\Core\Supports\Traits\PropertyInjection;

class PialeChannelView extends View
{
	use PropertyInjection;

	private $text;

	protected function template (Designer $designer)
	{
		$designer->addText([
			'🔸🔻🔹🔸🔻🔹🔸🔻🔹',
			'',
			$this->text,
			'',
			'🔸🔻🔹🔸🔻🔹🔸🔻🔹',
		]);
	}
}
