<?php

namespace tests\unit\Dilicom;

use atoum;

class RestClient extends atoum\test
{

    protected $tested_client;

    protected $http_connector_mock;

    public function beforeTestMethod()
    {
        $this->mockGenerator->orphanize("__construct");
        $this->http_connector_mock = new \mock\Dilicom\HttpConnector();

        $this->tested_client = new \Dilicom\RestClient("user", "password", \Dilicom\RestClient::ENV_TEST);
        $this->tested_client->setConnector($this->http_connector_mock);
    }

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

    public function testOnBadlyFormattedEbooksGetEbooksAvailabilitiesThrowsException()
    {
        $response = "Availability response";
        $this->calling($this->http_connector_mock)->get = $response;

        $this
            ->exception(
                function() {
                    $this->tested_client->getEbooksAvailabilities(array(
                        array("9770000000000", "3230000000000", 5),
                    ));
                }
            )
            ->isInstanceOf('\InvalidArgumentException')
            ->message
            ->contains("Given ebook is badly formed")
        ;
    }

    public function testWhenCalledProperlyGetEbooksAvailabilitiesReturnsCallsDilicomProperlyAndReturnsResult()
    {
        $response = "Availability response";
        $this->calling($this->http_connector_mock)->get = $response;

        $availability = $this->tested_client->getEbooksAvailabilities(array(
            array("ean13" => "9780000000000", "glnDistributor" => "3330000000000", "unitPrice" => 0),
            array("ean13" => "9770000000000", "glnDistributor" => "3230000000000", "unitPrice" => 5),
        ));

        $this->mock($this->http_connector_mock)
             ->call("get")
             ->withArguments(
                "/v3/hub-numerique-api/json/checkAvailability",
                null,
                array(
                    "auth" => array("user", "password"),
                    "verify" => true,
                    "debug" => false,
                    "query" => array(
                        "checkAvailabilityLines[0].ean13" => "9780000000000",
                        "checkAvailabilityLines[0].glnDistributor" => "3330000000000",
                        "checkAvailabilityLines[0].unitPrice" => "0",
                        "checkAvailabilityLines[1].ean13" => "9770000000000",
                        "checkAvailabilityLines[1].glnDistributor" => "3230000000000",
                        "checkAvailabilityLines[1].unitPrice" => "5",
                    )
                )
             )
             ->once()
             ;

        $this
            ->string($availability)
                ->isEqualTo($response)
        ;
    }

    public function testGetEbookAvailability()
    {
        $response = "Availability response";
        $this->calling($this->http_connector_mock)->get = $response;

        $availability = $this->tested_client->getEbookAvailability("9780000000000", "3330000000000", 749);

        $this
            ->string($availability)
                ->isEqualTo($response)
        ;
    }

    public function testGetOnixNotice()
    {
        // Mocks the connector to avoid sending real requests
        $response = "Hello World!";
        $this->calling($this->http_connector_mock)->get = $response;

        $this
            ->string($this->tested_client->getOnixNotice("9780000000000"))
                ->isEqualTo($response)
        ;
    }

    public function testDisableSslVerification()
    {
        $c = new \Dilicom\RestClient("user", "password", \Dilicom\RestClient::ENV_TEST);
        $this
            ->object($c->disableSslVerification())
                ->isEqualTo($c);
    }

    public function testInProductionDisableSslVerificationThrowsException()
    {
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
