<?php
namespace SRIO\Discovered;

use JsonRPC\Client as JsonRPCClient;

class Client 
{
    /**
     * Default protocol used if there's no protocol in the given address.
     *
     * @var string
     */
    const DEFAULT_PROTOCOL = 'http';

    /**
     * @var JsonRPCClient
     */
    protected $rpcClient;

    /**
     * Constructor.
     *
     * @param string $address
     * @throws \RuntimeException
     */
    public function __construct($address = null)
    {
        if (!$address) {
            $address = getenv('DISCOVERD').'/_goRPC_';

            if (!$address) {
                throw new \RuntimeException('Unable to find Discoverd address');
            }
        }

        $this->rpcClient = new JsonRPCClient($this->normalizeAddress($address));
        $this->rpcClient->debug = true;
    }

    /**
     * Subscribe to a service updates.
     *
     * @param $name
     * @return mixed
     */
    public function subscribe($name)
    {
        return $this->rpcClient->execute('Agent.Subscribe', array(
            'Name' => $name
        ));
    }

    /**
     * Normalize the client address.
     *
     * @param $address
     * @return string
     */
    protected function normalizeAddress($address)
    {
        if (strpos($address, '://') === -1) {
            $address = self::DEFAULT_PROTOCOL.'://'.$address;
        }

        return $address;
    }
} 