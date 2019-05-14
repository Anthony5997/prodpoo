<?php
declare(strict_types=1);

function printArray( string $sMessage='', array $aData ):void
{
    echo str_repeat('*', 70) . PHP_EOL;
    if (!empty($sMessage)) {
        echo $sMessage. PHP_EOL;
    }
    print_r($aData);
    echo PHP_EOL;
}

function printString( string $sMessage='', string $sValue ):void
{
    echo str_repeat('*', 70) . PHP_EOL;
    echo $sMessage . ' ' . $sValue . PHP_EOL;
    echo PHP_EOL;
}

function printSeparator():void
{
    echo str_repeat('*', 70) . PHP_EOL;
}
