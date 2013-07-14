<?php

namespace Firenote;

use Symfony\Component\Console\Application;

require __DIR__ . '/../bootstrap.php';

$app = new Application();
$app->add(new Command\ApplicationInit());
$app->run();