<?php

namespace Bot\Tests;


use BotFramework\Tests\TestCase as BotTestCase;
use BotFramework\Facilities\Factories\UpdateFactory;

class ExampleTest extends BotTestCase
{
//	use RefreshDatabase;

	/**
	 * @test
	 */
	public function example ()
	{
		$this->incomingUpdate(UpdateFactory::make());

		$this->assertEquals(1, 1);
	}
}
