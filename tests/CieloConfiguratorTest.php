<?php

namespace Tests;

use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Merchant;
use CSWeb\Cielo\CieloConfigurator;
use PHPUnit\Framework\TestCase;

class CieloConfiguratorTest extends TestCase
{
    public function testConfigurator()
    {
        $config = new CieloConfigurator('username', 'password', true);

        $this->assertInstanceOf(CieloConfigurator::class, $config);
        $this->assertInstanceOf(Merchant::class, $config->merchant());
        $this->assertInstanceOf(Environment::class, $config->environment());
        $this->assertIsBool($config->isSandbox());
        $this->assertTrue($config->isSandbox());
    }
}
