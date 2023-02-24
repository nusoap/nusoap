<?php

use Codeception\Test\Unit;
use NuSoap\Wsdl\Style;

require_once 'tests/_data/unit/server/MyClassThatDenifineMyWebServiceMethods.php';
require_once 'tests/_data/unit/server/myFunctions.php';

class ServerTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests

    /**
     * @throws ReflectionException
     */
    public function testSoapEnvelopRpcMustBeTheSame()
    {
        $server = new NuSoap\Server\Server();

        $_SERVER = [];
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SCRIPT_NAME'] = '/samples/server/server.php';
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['PHP_SELF'] = '/samples/server/server.php';
        $_SERVER['QUERY_STRING'] = 'wsdl';

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

                $host ='localhost';

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

        ob_start();
        $server->service(file_get_contents('php://input'));
        $wsdl = ob_get_contents();
        ob_clean();
        ob_flush();
        ob_end_flush();

        $wsdlControle = file_get_contents('tests/_data/unit/server/SoapEnvelopRpc.wsdl');

        $wsdlControle = $this->sanitizeWsdl($wsdlControle);
        $wsdl = $this->sanitizeWsdl($wsdl);

        $this->assertEquals($wsdlControle, $wsdl);

    }

    /**
     * @throws ReflectionException
     */
    public function testSoapEnvelopDocumentMustBeTheSame()
    {
        $server = new NuSoap\Server\Server();

        $_SERVER = [];
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SCRIPT_NAME'] = '/samples/server/server.php';
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['PHP_SELF'] = '/samples/server/server.php';
        $_SERVER['QUERY_STRING'] = 'wsdl';

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

                $host ='localhost';

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

        ob_start();
        $server->service(file_get_contents('php://input'));
        $wsdl = ob_get_contents();
        ob_clean();
        ob_flush();
        ob_end_flush();

        $wsdlControle = file_get_contents('tests/_data/unit/server/SoapEnvelopDocument.wsdl');

        $wsdlControle = $this->sanitizeWsdl($wsdlControle);
        $wsdl = $this->sanitizeWsdl($wsdl);

        $this->assertEquals($wsdlControle, $wsdl);

    }

    private function sanitizeWsdl($wsdl)
    {

        $wsdl = str_replace("\n", "", $wsdl);
        $wsdl = trim($wsdl);

        return $this->prettyPrintXml($wsdl);
    }

    private function prettyPrintXml($xml)
    {
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($xml);
        $dom->formatOutput = TRUE;
        return $dom->saveXML();
    }
}
