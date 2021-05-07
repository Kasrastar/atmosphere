<?php


namespace Bot\Views;


use BotFramework\Views\View;
use BotFramework\Facilities\Supports\Traits\PropertyInjection;
use BotFramework\Views\Designer;

class ExampleView extends View
{
	use PropertyInjection;

	private $text;
	private $photo;

	protected function template (Designer $design)
	{
		$design->addPhoto($this->photo);

		$design->addText([
			'allpha',
			'beta'
		]);
	}
}
