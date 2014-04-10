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

    public function testOnBadlyFormattedEbooksGetEbooksAvailabilitiesThrowsException()
    {
        $response = "Availability response";
        $this->mockGenerator->orphanize("__construct");
        $connector = new \mock\Dilicom\HttpConnector();
        $this->calling($connector)->get = $response;

        $c = new \Dilicom\RestClient("user", "password", \Dilicom\RestClient::ENV_TEST);
        $c->setConnector($connector);

        $this
            ->exception(
                function() use ($c) {
                    $c->getEbooksAvailabilities(array(
                        array("9770000000000", "3230000000000", 5),
                    ));
                }
            )
            ->isInstanceOf('\InvalidArgumentException')
            ->message
            ->contains("Given ebook is badly formed")
        ;
    }

    public function testWhenCalledProperlyGetEbooksAvailabilitiesReturnsExpectedResult()
    {
        $response = "Availability response";
        $this->mockGenerator->orphanize("__construct");
        $connector = new \mock\Dilicom\HttpConnector();
        $this->calling($connector)->get = $response;

        $c = new \Dilicom\RestClient("user", "password", \Dilicom\RestClient::ENV_TEST);
        $c->setConnector($connector);

        $availability = $c->getEbooksAvailabilities(array(
            array("ean13" => "9780000000000", "glnDistributor" => "3330000000000", "unitPrice" => 7),
            array("ean13" => "9770000000000", "glnDistributor" => "3230000000000", "unitPrice" => 5),
        ));

        $this->mock($connector)
             ->call("get")
             ->withArguments(
                "/v1/hub-numerique-api/json/checkAvailability",
                null,
                array(
                    "auth" => array("user", "password"),
                    "verify" => true,
                    "debug" => false,
                    "query" => array(
                        "checkAvailabilityLines[0].ean13" => "9780000000000",
                        "checkAvailabilityLines[0].glnDistributor" => "3330000000000",
                        "checkAvailabilityLines[0].unitPrice" => "7",
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
        $this->mockGenerator->orphanize("__construct");
        $connector = new \mock\Dilicom\HttpConnector();
        $this->calling($connector)->get = $response;

        $c = new \Dilicom\RestClient("user", "password", \Dilicom\RestClient::ENV_TEST);
        $c->setConnector($connector);

        $availability = $c->getEbookAvailability("9780000000000", "3330000000000", 749);

        $this
            ->string($availability)
                ->isEqualTo($response)
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
