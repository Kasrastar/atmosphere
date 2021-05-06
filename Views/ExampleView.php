<?php


namespace Bot\Views;


use BotFramework\Views\View;
use BotFramework\Views\ViewBuilder;

class ExampleView extends View
{
	private $photo;
	private $text;

	public function __construct ($photo, $text)
	{
		$this->text = $text;
		$this->photo = $photo;
	}

	protected function template (ViewBuilder $builder)
	{
		$builder->photo($this->photo);

		$builder->text(function () use ($builder) {
			return [
				$builder->line($this->text),
			];
		});
	}
}
