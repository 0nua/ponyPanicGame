<?php

use Dotenv\Dotenv;
use PonyPanic\PonyPanicGameCommand;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/vendor/autoload.php';

const PROJECT_DIR = __DIR__;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new Application();
$app->add(new PonyPanicGameCommand());
$app->run();