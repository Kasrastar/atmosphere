<?php

namespace Bot\Tests;


use Bot\Models\Message;
use Bot\Views\ExampleView;
use BotFramework\Views\ViewParser;
use BotFramework\Tests\TestCase as Bot_TestCase;
use BotFramework\Facilities\Factories\UpdateFactory;

class ExampleTest extends Bot_TestCase
{
//	use RefreshDatabase;

	/**
	 * @test
	 */
	public function example ()
	{
		$update = UpdateFactory::make();
		$this->incomingUpdate($update);

		Message::create([
			'from' => \user()->getId(),
			'message' => \message()->getText(),
		]);

		$this->assertEquals(1, 1);
	}
}
