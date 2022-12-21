<?php

class MyClassThatDenifineMyWebServiceMethods
{
    public function demonstration(
        $parameterOne = '$parameterOne',
        $parameterTwo = '$parameterTwo',
        $parameterThree = '$parameterThree'
    ) {
        $response = new SimpleXMLElement('<response/>');

        $response->addChild('parameterOne', $parameterOne);
        $response->addChild('parameterTwo', $parameterTwo);
        $response->addChild('parameterThree', $parameterThree);

        return $response->asXML();
    }
}