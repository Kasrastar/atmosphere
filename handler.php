<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Kernel;
use Atmosphere\Core\Boot;
use Atmosphere\Facade\LifeCycle;

$boot = Boot::loadConfig(__DIR__)->turnOn(new Kernel);

require_once 'routes/router.php';
LifeCycle::takeInto($boot->getUpdates());
