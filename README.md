# PHP client to connect to the Dilicom Hub

[![Build Status](https://travis-ci.org/Koin/php-dilicom.png?branch=master)](https://travis-ci.org/Koin/php-dilicom)

## About

php-dilicom is a PHP client for using the Dilicom Hub.

Dilicom: http://www.dilicom.net/  
Dilicom Hub API: https://hub-dilicom.centprod.com/documentation/

```php
# Very simple!
$client = new Dilicom\RestClient("MY_GLN", "MY_PASSWORD", Dilicom\RestClient::ENV_PROD);
echo $client->getOnixNotice("9782756406053", "GLN_CONTRACTOR", "GLN_DISTRIBUTOR");
```

Output:
```xml
<?xml version="1.0" encoding="UTF-8"?><ONIXMessage release="3.0" xmlns="http://www.editeur.org/onix/3.0/reference">
<Header>
    ...
</Header>
<Product>
    <RecordReference>EDEN8564-epub</RecordReference>
    <NotificationType>02</NotificationType>
    <ProductIdentifier>
        <ProductIDType>03</ProductIDType>
        <IDValue>9782756406053</IDValue>
    </ProductIdentifier>
    <DescriptiveDetail>
        <ProductComposition>00</ProductComposition>
        <ProductForm>EA</ProductForm>
        <ProductFormDetail>E101</ProductFormDetail>
        <ProductFormDetail>E200</ProductFormDetail>
        <EpubTechnicalProtection>03</EpubTechnicalProtection>
        <EpubUsageConstraint>
            <EpubUsageType>02</EpubUsageType>
            <EpubUsageStatus>03</EpubUsageStatus>
        </EpubUsageConstraint>
        <EpubUsageConstraint>
            <EpubUsageType>03</EpubUsageType>
            <EpubUsageStatus>03</EpubUsageStatus>
        </EpubUsageConstraint>
        <EpubUsageConstraint>
            <EpubUsageType>04</EpubUsageType>
            <EpubUsageStatus>02</EpubUsageStatus>
            <EpubUsageLimit>
                <Quantity>6</Quantity>
                <EpubUsageUnit>06</EpubUsageUnit>
            </EpubUsageLimit>
        </EpubUsageConstraint>
        <TitleDetail>
        <TitleType>01</TitleType>
        <TitleElement>
            <TitleElementLevel>01</TitleElementLevel>
            <TitleText>L'Apprenti assassin</TitleText>
```

## Installation

The recommended way to install php-dilicom is through [Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add php-dilicom as a dependency
php composer.phar require pkoin/php-dilicom:dev-master
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Available APIs

* onix/getNotice?ean13=<ean13>&glnDistributor=<gln>&glnDistributor=<gln>: Get an ONIX notice for a given EAN13

## You want to contribute?

* Fork the project.
* Create a topic branche.
* Make your feature addition or bug fix.
* Add tests for it, this is important.
* Commit, do not mess with history.
* Send me a pull request.

## Unit testing

php-dilicom uses Atoum for unit testing.
In order to run the unit tests, you'll first need to install the dependencies of the project using Composer: `php composer.phar install --dev`.  
You can then run the tests using `vendor/bin/atoum -bf tests/unit/bootstrap.php -d tests/unit`.

## License

Under the [WTFPL v2.0](http://en.wikipedia.org/wiki/WTFPL)
