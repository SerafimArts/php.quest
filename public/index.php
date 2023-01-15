<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

/**
 * Create new application instance
 */
$kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);

/**
 * Create new request from global environment
 */
$request = Request::createFromGlobals();

/**
 * Handle request and receive response
 */
$response = $kernel->handle($request);
$response->send();

/**
 * Terminate application
 */
$kernel->terminate($request, $response);
