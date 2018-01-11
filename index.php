<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

$system = new Myframework\MMVC\Controller();
$system->run();
