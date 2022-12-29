<?php

require_once 'tests/_data/unit/server/MyClassThatDenifineMyWebServiceMethods.php';

function demonstration(
    $parameterOne = '$parameterOne',
    $parameterTwo = '$parameterTwo',
    $parameterThree = '$parameterThree'
) {
    return (new MyClassThatDenifineMyWebServiceMethods())
        ->demonstration(
            $parameterOne,
            $parameterTwo,
            $parameterThree
        );
}

function uploadFile(
    $filename,
    $file
) {
    return (new MyClassThatDenifineMyWebServiceMethods())
        ->uploadFile(
            $filename,
            $file
        );
}
