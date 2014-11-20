<?php
namespace SRIO\Discovered;

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

        $components = parse_url($this->normalizeAddress($address));
        $this->rpcClient = new JsonRPCClient($components['host'], $components['port'], $components['path'], array(
            'dial_headers' => array(
                'Accept' => 'application/vnd.flynn.rpc-hijack+json'
            )
        ));
    }

    /**
     * Subscribe to a service updates.
     *
     * @param $name
     * @return mixed
     */
    public function subscribe($name)
    {
        return $this->rpcClient->call('Agent.Subscribe', array(
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