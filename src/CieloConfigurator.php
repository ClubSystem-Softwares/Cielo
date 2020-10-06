<?php

namespace CSWeb\Cielo;

use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Merchant;

/**
 * CieloConfigurator
 *
 * As chaves de acesso são enviadas via e-mail, entretanto, as chaves
 * do sandbox podem ser geradas através de um site disponibilizado
 * pela Cielo
 *
 * @author  Matheus Lopes Santos <fale_com_lopez@hotmail.com>
 * @version 1.0.0
 * @package Cielo
 * @see     https://cadastrosandbox.cieloecommerce.cielo.com.br/
 */
class CieloConfigurator
{
    protected $merchant;

    protected $env;

    protected $sandbox;

    public function __construct(string $username, string $password, bool $sandbox = false)
    {
        $this->sandbox  = $sandbox;
        $this->merchant = new Merchant($username, $password);

        $this->env = $this->isSandbox()
            ? Environment::sandbox()
            : Environment::production();
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function merchant(): Merchant
    {
        return $this->merchant;
    }

    public function environment(): Environment
    {
        return $this->env;
    }
}
