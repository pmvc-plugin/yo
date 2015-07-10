<?php
PMVC\Load::mvc();
PMVC\addPlugInFolder('../');
class YoTest extends PHPUnit_Framework_TestCase
{
    function testInit()
    {
        $yo = \PMVC\plug('yo');
        $this->assertContains('fast_route',var_export($yo,true));
    }

    function testGet()
    {
        $test_name = 'abc';
        $yo = \PMVC\plug('yo');
        $url = \PMVC\plug('url',array(
            'REQUEST_URI'=>'/yo/hello/'.$test_name,
            'SCRIPT_NAME'=>'/yo/'
        ));
        $yo['method']='GET';
        $yo->get('/hello/{name}',function($m,$f) use ($yo){
            $yo['test_name']=$f['name'];
        });
        $yo->process();
        $this->assertEquals($test_name,$yo['test_name']);
    }
}
