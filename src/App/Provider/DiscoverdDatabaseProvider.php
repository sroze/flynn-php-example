<?php
namespace App\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SRIO\Discoverd\Client;

class DiscoverdDatabaseProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $doctrineInitializer = $app['dbs.options.initializer'];
        $app['dbs.options.initializer'] = $app->protect(function () use ($app, $doctrineInitializer) {
            static $initialized = false;
            if ($initialized) {
                return;
            }

            // Get the PostgreSQL server address
            $postgresServiceName = getenv('FLYNN_POSTGRES');
            if ($postgresServiceName === false) {
                throw new \RuntimeException('Unable to get the PostgreSQL service name');
            }

            try {
                $client = new Client();
                $result = $client->subscribe($postgresServiceName);

                $options = array(
                    'driver'    => 'pdo_pgsql',
                    'dbname'    => getenv('PGDATABASE'),
                    'user'      => getenv('PGUSER'),
                    'password'  => getenv('PGPASSWORD'),
                    'charset'   => 'utf8',
                );

                $serverAddress = $result['Addr'];
                $addressParts = explode(':', $serverAddress);
                if (count($addressParts) > 1) {
                    $options['port'] = $addressParts[1];
                    $serverAddress = $addressParts[0];
                }

                $options['host'] = $serverAddress;
                $app['db.options'] = $options;

                $initialized = true;

                // Call doctrine initializer
                $doctrineInitializer();
            } catch (\Exception $e) {
                throw new \RuntimeException(sprintf(
                    'Unable to get the PostgreSQL server address from Discoverd server: %s',
                    $e->getMessage()
                ));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
