<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Kernel;
use Atmosphere\Core\Boot;
use Atmosphere\Facade\LifeCycle;

require_once 'routes/router.php';

$boot = Boot::loadConfig(__DIR__)->turnOn(new Kernel);
LifeCycle::takeInto($boot->getUpdatesViaWebhook());
