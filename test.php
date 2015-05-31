<?php
include_once('vendor/pmvc/pmvc/include.php');
PMVC\setPlugInFolder('../');

class YoTest extends PHPUnit_Framework_TestCase
{
    function testInit()
    {
        $yo= \PMVC\plug('yo');
        $this->assertContains('fast_route',var_export($yo,true));
    }

    function testGet()
    {
        $test_name = 'abc';
        $yo = \PMVC\plug('yo');
        $url = \PMVC\plug('url');
        $url->set(array(
            'REQUEST_URI'=>'/yo/hello/'.$test_name,
            'SCRIPT_NAME'=>'/yo/'
        ));
        $yo->set('method','GET');
        $yo->get('/hello/{name}',function($m,$f) use ($yo){
            $yo->set('test_name',$f->get('name'));
        });
        $yo->process();
        $this->assertEquals($test_name,$yo->get('test_name'));
    }
}
