CSWEB Cielo
===

Package de integração com a Cielor

Instalação
----

Para instalação, utilize o composer

```sh
$ composer require csweb/composer
```

Testes
----

Para execução dos testes, crie uma conta de testes na
[Cielo Developer Center](https://cadastrosandbox.cieloecommerce.cielo.com.br/).

Após conseguir suas credenciais, salve os dados no arquivo `phpunit.xml`
e execute os testes

```sh
$ vendor/bin/phpunit
```

# Atenção
Este package encontra em funcionamento as seguintes funcionalidades
* Pagamento com Cartão
* Cancelamento de Cobrança
