<?php


namespace Bot\App\Views;


use BotFramework\App\Views\View;
use BotFramework\App\Views\Designer;
use BotFramework\Core\Supports\Traits\PropertyInjection;

class SpecialView extends View
{
	use PropertyInjection;

	private $text;

	protected function template (Designer $designer)
	{
		$designer->addText([
			'🟩🟩🟩🟩🟩🟩🟩🟩',
			'',
			$this->text,
			'',
			'🟩🟩🟩🟩🟩🟩🟩🟩'
		]);
	}
}
