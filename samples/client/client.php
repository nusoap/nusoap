<?php

use NuSoap\Client\Client;

require_once '../../vendor/autoload.php';

// Define o usuário e senha
$login = 'login';
$senha = 'senha';

// Opções de configurações do cliente WSDL
$options = array(
    'soap_version' => 1,
    'login'        => $login,
    'password'     => $senha
);

// Cria o objeto SOAP com a URL do serviço
$client = new Client("http://localhost/samples/server/server.php?wsdl");

var_dump($client->call('demonstration'));