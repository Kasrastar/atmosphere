<?php


require_once __DIR__ . '/vendor/autoload.php';


use BotFramework\LifeCycle;
use BotFramework\Providers\Boot;


$updates = Boot::turnOn()->getUpdates();

LifeCycle::takeInto($updates);
