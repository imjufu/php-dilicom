<?php

namespace tests\unit\Dilicom;

use atoum;

class RestClient extends atoum\test
{
    public function testConstructWithABadEnv()
    {
        $this
            ->exception(
                function() {
                    $c = new \Dilicom\RestClient("user", "password", "unknown");
                }
            )
                ->isInstanceOf('\InvalidArgumentException')
                ->message
                    ->contains("no server for env unknown")
        ;
    }

    public function testGetOnixNotice()
    {
        // Mocks the connector to avoid sending real requests
        $response = "Hello World!";
        $this->mockGenerator->orphanize("__construct");
        $connector = new \mock\Dilicom\HttpConnector();
        $this->calling($connector)->get = $response;

        $c = new \Dilicom\RestClient("user", "password", \Dilicom\RestClient::ENV_TEST);
        $c->setConnector($connector);

        $this
            ->string($c->getOnixNotice("9780000000000"))
                ->isEqualTo($response)
        ;
    }

    public function testDisableSslVerification()
    {
        $c = new \Dilicom\RestClient("user", "password", \Dilicom\RestClient::ENV_TEST);
        $this
            ->object($c->disableSslVerification())
                ->isEqualTo($c);

        $c = new \Dilicom\RestClient("user", "password", \Dilicom\RestClient::ENV_PROD);
        $this
            ->exception(
                function() use ($c) {
                    $c->disableSslVerification();
                }
            )
                ->isInstanceOf('\InvalidArgumentException')
                ->message
                    ->contains("SSL certificate validation is mandatory in production!")
        ;
    }
}
