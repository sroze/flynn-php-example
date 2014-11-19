<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->get('/', function() {
    return new Response('PHP application is working successfully !');
});

$app->run();
