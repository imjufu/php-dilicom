<?php

namespace Dilicom;

/**
 * Connector interface
 * Used to send requests to Dilicom
 */
interface ConnectorInterface
{
    /**
     * Constructor
     *
     * @param string $base_url Base URL of the web service
     * @param array  $config   Configuration settings
     */
    public function __construct($base_url="", $config=null);

    /**
     * Get a response from a given URL
     *
     * @param  string   $uri        Relative URL of the web service
     * @param  array    $headers    HTTP headers to set on request
     * @param  array    $options    Request options: debug, SSL cert validation,...
     * @return string
     */
    public function get($uri=null, $headers=null, $options=array());
}
