<?php
PMVC\Load::plug();
PMVC\addPlugInFolders(['../']);
class YoTest extends PHPUnit_Framework_TestCase
{
    function testInit()
    {
        $method = 'GET';
        \PMVC\plug('controller')
            ->getRequest()
            ->setMethod($method);
        $yo = \PMVC\plug('yo');
        $this->assertEquals($method,$yo['method']);
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
        $adapter = $yo->get('/hello/{name}',function($m,$f) use ($yo){
            $yo['test_name']=$f['name'];
        });
        $yo->process();
        $this->assertEquals($test_name,$yo['test_name']);
        $this->assertContains('Adapter',print_r($adapter,true));
    }
}
