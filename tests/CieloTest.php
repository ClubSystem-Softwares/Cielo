<?php

namespace Tests;

use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\Sale;
use CSWeb\Cielo\Cielo;
use CSWeb\Cielo\CieloConfigurator;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class CieloTest extends TestCase
{
    public function testInstance()
    {
        $cielo = $this->getCielo();

        $this->assertInstanceOf(Cielo::class, $cielo);
    }

    public function testSaleCreation()
    {
        $sale = $this->getSale('4111111111111111');

        $cielo    = $this->getCielo();
        $response = $cielo->generate($sale);

        $this->assertIsString($response);
    }

    public function testInvalidData()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Você deve fornecer um número de cartão de crédito');

        $sale = $this->getSale();

        $cielo    = $this->getCielo();
        $response = $cielo->generate($sale);
    }

    public function getSale($creditCard = null): Sale
    {
        $sale = new Sale(1);

        $addressData = new stdClass();

        $addressData->Street   = 'Rua General Carneiro';
        $addressData->Number   = 12;
        $addressData->District = 'Centro';
        $addressData->City     = 'Montes Claros';
        $addressData->State    = 'MG';
        $addressData->Country  = 'BRA';
        $addressData->ZipCode  = 39401000;

        $sale->customer('João da Silva')
             ->setIdentity('12345678909')
             ->setIdentityType('CPF')
             ->address()
             ->populate($addressData);

        $sale->payment((10 * 100))
             ->setType(Payment::PAYMENTTYPE_CREDITCARD)
             ->creditCard(123, 'Visa')
             ->setExpirationDate('12/2030')
             ->setCardNumber($creditCard)
             ->setHolder('Comprador de Testes')
             ->setSaveCard(false);

        return $sale;
    }

    public function getCielo(): Cielo
    {
        return new Cielo(
            new CieloConfigurator(getenv('CIELO_MERCHANT_ID'), getenv('CIELO_MERCHANT_SECRET'), true)
        );
    }
}
