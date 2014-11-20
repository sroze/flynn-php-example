<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\DoctrineServiceProvider;
use App\Provider\DiscoverdDatabaseProvider;
use App\Controller\StatusController;

$app = new Silex\Application();
$app->register(new DoctrineServiceProvider());
$app->register(new DiscoverdDatabaseProvider());

$app->get('/', function() {
    return new Response('PHP application is working successfully !');
});

$app->mount('/status', new StatusController());
$app->run();
