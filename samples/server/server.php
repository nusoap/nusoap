<?php

use NuSoap\Server\Server;
use NuSoap\Wsdl\Style;

require_once '../../vendor/autoload.php';

require_once './myFunctions.php';


$server = new Server();
$server->configureWsdl('nusoap', 'localhost');
$server->wsdl->schemaTargetNamespace = 'http://localhost';

$server->soap_defencoding = 'UTF-8';
$server->decode_utf8  = false;
$server->encode_utf8  = true;

foreach (get_class_methods(MyClassThatDenifineMyWebServiceMethods::class) as $method) {
    if (strstr($method, '__construct')) {
        continue;
    }

    $refletionMethod = new ReflectionMethod(MyClassThatDenifineMyWebServiceMethods::class, $method);

    if ($refletionMethod->isPublic() && !$refletionMethod->isStatic()) {
        $params = [];

        foreach($refletionMethod->getParameters() as $parameter) {
            $params[$parameter->name] = 'xsd:string';
        }



        $host = $_SERVER['SERVER_NAME'];

        $server->register(
            $method,
            $params,
            ['return' => 'xsd:string'],
            "urn:$host",
            "urn:$host#$method",
            Style::RPC,
            'encoded'
        );
    }

}

$server->service(file_get_contents('php://input'));

