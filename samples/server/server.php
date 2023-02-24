<?php

use NuSoap\Server\Server;
use NuSoap\Wsdl\Style;

require_once '../../vendor/autoload.php';

require_once './myFunctions.php';


$server = new Server();
$server->configureWsdl('nusoap', 'localhost');
$server->wsdl->schemaTargetNamespace = 'http://localhost';


foreach (get_class_methods(MyClassExample::class) as $method) {
    if (strstr($method, '__construct')) {
        continue;
    }

    $refletionMethod = new ReflectionMethod(MyClassExample::class, $method);

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
            Style::DOCUMENT,
            'encoded'
        );
    }

}

$server->service(file_get_contents('php://input'));

