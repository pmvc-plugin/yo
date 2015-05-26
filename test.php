<?php
include_once('vendor/pmvc/pmvc/include.php');
PMVC\setPlugInFolder('../');

class YoTest extends PHPUnit_Framework_TestCase
{
    function testInit()
    {
        $yo= PMVC\plug('yo');
        $this->assertContains('FastRoute',var_export($yo,true));
    }
}
