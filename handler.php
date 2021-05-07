<?php


require_once __DIR__ . '/vendor/autoload.php';


use BotFramework\LifeCycle;
use BotFramework\Providers\Boot;
use Bot\Providers\MiddlewareProvider;
use Bot\Providers\ScenarioProvider;


$updates = Boot::getUpdates(__DIR__);
LifeCycle::takeInto($updates, MiddlewareProvider::register(), ScenarioProvider::register());
