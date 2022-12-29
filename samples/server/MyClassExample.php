<?php

class MyClassExample
{
    public function demonstration(
        $parameterOne = '$parameterOne',
        $parameterTwo = '$parameterTwo',
        $parameterThree = '$parameterThree'
    ) {
        sleep(5);
        $response = new SimpleXMLElement('<response/>');

        $response->addChild('parameterOne', $parameterOne);
        $response->addChild('parameterTwo', $parameterTwo);
        $response->addChild('parameterThree', $parameterThree);

        return $response->asXML();
    }

    public function uploadFile($filename, $file)
    {
        $response = new SimpleXMLElement('<response/>');

        $response->addChild('description', 'We received your file');

        return $response->asXML();
    }
}
