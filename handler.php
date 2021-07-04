<?php


require_once __DIR__ . '/vendor/autoload.php';


use Atmosphere\LifeCycle;
use Atmosphere\Providers\Boot;


$updates = Boot::turnOn()->getUpdates();

LifeCycle::takeInto($updates);
