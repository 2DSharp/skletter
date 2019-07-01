<?php

use Greentea\Core\Application;

require_once __DIR__ . '../vendor/autoload.php';

$dependencies = include_once(__DIR__ . '/Dependencies.php');

$app = new Application($dependencies);
