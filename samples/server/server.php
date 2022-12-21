<?php

use NuSoap\Server\Server;
use NuSoap\Wsdl\Style;

require_once '../../vendor/autoload.php';

require_once './MyClassThatDenifineMyWebServiceMethods.php';


$urn = 'urn:localhost';

$server = new Server();
$server->configureWsdl('nusoap', $urn);
$server->wsdl->schemaTargetNamespace = $urn;

$server->soap_defencoding = 'UTF-8';
$server->decode_utf8  = false;
$server->encode_utf8  = true;

$server->wsdl->addComplexType(
    'Item',
    'complexType',
    'struct',
    'all',
    '',
    [
        'id' => ['name' => 'id', 'type' => 'xsd:int'],
        'title'=> ['name' => 'title', 'type' => 'xsd:string'],
        'body'=> ['name' => 'body', 'type' => 'xsd:string'],
        'created'=> ['name' => 'created', 'type' => 'xsd:string'],
        'last_update' => ['name' => 'last_update', 'type' => 'xsd:string']
    ]
);

$server->register('read',
    [  // request
        'ws_user' => 'xsd:string',     // required
        'ws_pass' => 'xsd:string',     // required
        'id'      => 'xsd:int'         // opcional
    ],
    [  // response
        'Success' => 'xsd:boolean',
        'Message' => 'xsd:string',
        'Rows'    => 'xsd:int',
        'List'    => 'tns:List'
    ],
    'urn:wsNotes',                   // namespace
    'urn:wsNotes#List',              // accion SOAP
    Style::RPC,                           // estilo
    'encoded',                       // tipo de uso
    'Obtiene las notas registradas'  // documentacion
);

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


        $server->register(
            'demonstration',
            $params,
            ['return' => 'xsd:string'],
            $urn,
            "$urn#a",
            Style::RPC,
            'encoded'
        );
    }

}

$HTTP_RAW_POST_DATA = $HTTP_RAW_POST_DATA ?? '';

$server->service(file_get_contents('php://input'));

