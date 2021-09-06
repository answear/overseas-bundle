# Overseas pickup point bundle

Overseas integration for Symfony.  
Documentation of the API can be found here: https://api.overseas.hr/apiinfo

## Installation

* install with Composer

```
composer require git@github.com:answear/overseas-bundle.git
```

`Answear\OverseasBundle\AnswearOverseasBundle::class => ['all' => true],`  
should be added automatically to your `config/bundles.php` file by Symfony Flex.

## Setup

* provide required config data: `environment` and `apiKey`

```yaml
# config/packages/answear_overseas.yaml
answear_overseas:
    environment: test|prod
    apiKey: yourApiKey
    logger: yourCustomLoggerService #default: null
```

Logger service must implement `Psr\Log\LoggerInterface` interface.

## Usage

### Get ParcelShops

```php
/** @var \Answear\OverseasBundle\Service\ParcelShopsService $parcelShopService **/
$parcelShopService->get();
```

will return `\Answear\OverseasBundle\Response\ParcelShopsResult` object.

### Error handling

- `Answear\OverseasBundle\Exception\ServiceUnavailableException` for all `GuzzleException`
- `MalformedResponseException` for partner other errors
- `Answear\OverseasBundle\Exception\BadRequestException` if validation failed
- TODO

Final notes
------------

Feel free to open pull requests with new features, improvements or bug fixes. The Answear team will be grateful for any comments.

