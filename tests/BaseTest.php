<?php

namespace CobreFacil;

use CobreFacil\Exceptions\InvalidParamsException;
use CobreFacil\Exceptions\ResourceNotFoundException;
use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /** @var CobreFacil */
    protected $cobrefacil;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        Dotenv::createImmutable(__DIR__ . '/..')->load();
    }

    protected function setUp(): void
    {
        $this->cobrefacil = $this->createCobreFacilClient();
    }

    protected function createCobreFacilClient(): CobreFacil
    {
        $appId = $_ENV['APP_ID'];
        $secret = $_ENV['SECRET'];

        $cobreFacil = new CobreFacil($appId, $secret);
        $cobreFacil->setProduction(false);

        if (! empty($_ENV['BASE_URI'])) {
            $httpClient = $this->createHttpClient();
            $cobreFacil->setHttpClient($httpClient);
        }

        return $cobreFacil;
    }

    protected function createHttpClient(): ClientInterface
    {
        return new Client([
            'base_uri' => $_ENV['BASE_URI'],
            'timeout' => 0,
        ]);
    }

    protected function getLastCardId(): string
    {
        return $this->getLastCard()['id'];
    }

    protected function getLastCard(): array
    {
        return $this->cobrefacil->card->search()[0];
    }

    protected function getLastCustomerId(): string
    {
        return $this->getLastCustomer()['id'];
    }

    protected function getLastCustomer(): array
    {
        return $this->cobrefacil->customer->search()[0];
    }

    protected function waitAsyncRequestBeProcessed(): void
    {
        sleep(3);
    }

    protected function assertInvalidParamsException(array $expectedErrors, InvalidParamsException $exception)
    {
        $this->assertEquals('Parâmetros inválidos.', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals($expectedErrors, $exception->getErrors());
    }

    protected function assertResourceNotFoundException(ResourceNotFoundException $exception, string $expectedMessage)
    {
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}
