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
    $output = '# PostgreSQL'."\n".'Service name: '.$postgresServiceName."\n";

    try {
        $client = new Client();
        $result = $client->subscribe($postgresServiceName);

        $output .= 'Address: '.$result['Addr']."\n";
        $output .= 'Online: '.($result['Online'] ? 'Yes' : 'No')."\n";
        $output .= 'Created: '.$result['Created']."\n";
        $output .= 'Attributes:'."\n";
        foreach ($result['Attrs'] as $key => $value) {
            $output .= '    '.$key.' = '.$value."\n";
        }
    } catch (\Exception $e) {
        $output .= '[EXCEPTION] '.$e->getMessage()."\n";
        $output .= $e->getTraceAsString();
    }

    return $output;
});

$app->run();
