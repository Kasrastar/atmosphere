<?php

namespace Bot\Tests;


use Atmosphere\Tests\TestCase as BotTestCase;
use Atmosphere\Facilities\Factories\UpdateFactory;

class ExampleTest extends BotTestCase
{
	//	use RefreshDatabase;

	/**
	 * @test
	 */
	public function example ()
	{
		$this->incomingUpdate(UpdateFactory::make());

		// your tests here
	}
}
