<?php


namespace Bot\App\Views;


use BotFramework\App\Views\View;
use BotFramework\App\Views\Designer\Text;
use BotFramework\App\Views\Designer\Dice;
use BotFramework\App\Views\Designer\Photo;
use BotFramework\App\Views\Designer\Video;
use BotFramework\App\Views\Designer\Designer;
use BotFramework\App\Views\Designer\PhotoFromURL;
use BotFramework\Core\Supports\Traits\PropertyInjection;

class SpecialView extends View
{
	use PropertyInjection;

	private $text;

	protected function template (Designer $designer)
	{
		$designer->add(new Text([
			'🟩🟩🟩🟩🟩🟩🟩🟩',
			'',
			$this->text,
			'',
			'🟩🟩🟩🟩🟩🟩🟩🟩'
		]));

		$designer->add(new Dice());
		$designer->add(new Text(['ssss']));
	}
}
