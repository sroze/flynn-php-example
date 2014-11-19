<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use SRIO\Discovered\Client;

$app = new Silex\Application();

$app->get('/', function() {
    return new Response('PHP application is working successfully !');
});

$app->get('/status', function() {
    $postgresServiceName = getenv('FLYNN_POSTGRES');
    $output = 'PostgreSQL service name: '.$postgresServiceName."\n";

    try {
        $client = new Client();
        $result = $client->subscribe($postgresServiceName);

        var_dump($result);
    } catch (\Exception $e) {
        $output .= '[EXCEPTION] '.$e->getMessage()."\n";
        $output .= $e->getTraceAsString();
    }

    return $output;
});

$app->run();
