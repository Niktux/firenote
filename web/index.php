<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new MyApp\Application(new Firenote\Configuration\Yaml());

$app->enableDebug()->enableProfiling();

$app->mountProviders();
$app->initializeAdminLayout();

$app->run();
