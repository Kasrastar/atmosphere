<?php


namespace Bot\Views;


use BotFramework\Views\View;
use BotFramework\Views\InjectProperties;
use BotFramework\Views\ViewBuilder;

class ExampleView extends View
{
	use InjectProperties;

	private $text;
	private $photo;

	protected function template (ViewBuilder $builder)
	{
		$builder->photo($this->photo);

		$builder->text([
			'allpha',
			'beta'
		]);
	}
}
