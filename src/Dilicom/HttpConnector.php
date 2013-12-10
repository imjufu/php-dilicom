<?php

namespace Dilicom;

use Guzzle\Http\Client as GuzzleConnector;

/**
 * HTTP connector
 * Uses the Guzzle HTTP client
 */
class HttpConnector extends GuzzleConnector implements ConnectorInterface
{
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
        $response = parent::get($uri, $headers, $options);
        return $response->send();
    }
}
