<?php

namespace Dilicom;

use Guzzle\Http\Client as GuzzleClient;

/**
 * HTTP connector
 * Uses the Guzzle HTTP client
 */
class HttpConnector implements ConnectorInterface
{
    /**
     * Guzzle HTTP client to send requests
     * @var Guzzle\Http\Client
     */
    protected $client;

    /**
     * Constructor
     *
     * @param string $base_url Base URL of the web service
     * @param array  $config   Configuration settings
     */
    public function __construct($base_url="", $config=null)
    {
        $this->client = new GuzzleClient($base_url, $config);
    }

    /**
     * Get a response from a given URL
     *
     * @param  string   $uri        Relative URL of the web service
     * @param  array    $headers    HTTP headers to set on request
     * @param  array    $options    Request options: debug, SSL cert validation,...
     * @return string
     */
    public function get($uri=null, $headers=null, $options=array())
    {
        // To be compatible with Guzzle 3.7.4, we have to remove the "debug" option
        // otherwise this will output a debug message even if the value is false :(
        // @see https://github.com/guzzle/guzzle/releases/tag/v3.8.0
        // "The debug request parameter now checks if it is truthy rather than if it exists"
        if (isset($options["debug"]) && true !== $options["debug"]) {
            unset($options["debug"]);
        }

        $request = $this->client->get($uri, $headers, $options);
        return $request->send()->getBody(true);
    }
}
