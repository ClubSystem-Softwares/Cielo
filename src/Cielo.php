<?php

namespace CSWeb\Cielo;

use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Request\CieloRequestException;
use Cielo\API30\Ecommerce\Sale;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Cielo
 *
 * Classe que engloba os métodos de chamada para operações
 * com a API da Cielo
 *
 * De alguma forma, o sever de produção não possuia a correta configuração
 * para o cURL. Assim, definimos a constante CURL_SSLVERSION_TLSv1_2
 * para o correto funcionamento
 *
 * @author  Matheus Lopes Santos <fale_com_lopez@hotmail.com>
 * @version 1.0.0
 * @package Cielo
 * @see https://github.com/DeveloperCielo/API-3.0-PHP/issues/31
 * @see https://github.com/DeveloperCielo/API-3.0-PHP/issues/31#issuecomment-352098621
 */
class Cielo
{
    /**
     * @var CieloEcommerce
     */
    protected $ecommerce;

    public function __construct(CieloConfigurator $config, LoggerInterface $logger = null)
    {
        if (!defined('CURL_SSLVERSION_TLSv1_2')) {
            define('CURL_SSLVERSION_TLSv1_2', 6);
        }

        $this->ecommerce = new CieloEcommerce(
            $config->merchant(),
            $config->environment(),
            $logger
        );
    }

    public function generate(Sale $sale)
    {
        try {
            $sale    = $this->ecommerce->createSale($sale);
            $payment = $sale->getPayment();

            $this->ecommerce->captureSale($payment->getPaymentId());

            return $payment->getPaymentId();
        } catch (CieloRequestException $exception) {
            if ($error = $exception->getCieloError()) {
                throw new IntegrationErrorsException($error);
            } else {
                throw new RuntimeException($exception->getMessage());
            }
        }
    }

    public function cancelar($paymentId)
    {
        try {
            $this->ecommerce->cancelSale($paymentId);

            return true;
        } catch (CieloRequestException $exception) {
            $error = $exception->getCieloError();

            if ($error) {
                return new IntegrationErrorsException($error);
            } else {
                throw new RuntimeException($exception->getMessage());
            }
        }
    }

    public function venda(string $paymentId)
    {
        return $this->ecommerce->getSale($paymentId);
    }
}
