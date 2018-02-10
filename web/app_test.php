<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

umask(0000);
require __DIR__.'/../vendor/autoload.php';

$filesPaths = [__DIR__.'/../.env.dist'];
$overwrittenDotEnvFilePath = __DIR__.'/../.env';

if (is_readable($overwrittenDotEnvFilePath)) {
    $filesPaths[] = $overwrittenDotEnvFilePath;
}

$dotenv = new Dotenv();
$dotenv->load(...$filesPaths);

Debug::enable();
$kernel = new AppKernel('test', false);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
