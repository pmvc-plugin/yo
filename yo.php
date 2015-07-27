<?php
namespace PMVC\PlugIn\yo;

${_INIT_CONFIG}[_CLASS] = 'PMVC\PlugIn\yo\yo';

class yo extends \PMVC\PLUGIN
{
    private $route;

    public function init()
    {
        $controller = \PMVC\getC(); 
        if (!$controller) {
            $controller = new \PMVC\ActionController();
        }
        $this->setDefaultAlias($controller);
        \PMVC\plug('dispatcher')->attach($this,'MapRequest');
        $this->route=\PMVC\plug('fast_route');
        \PMVC\plug('url')->setEnv(array(
            'REQUEST_URI',
            'SCRIPT_NAME'
        ));
        $this['method'] = $this->getRequest()->getMethod();
    }

    public function getRoutes()
    {
        return $this->route->getRoutes();
    }

    public function onMapRequest()
    {
        $request = $this->getRequest();
        $uri = \PMVC\plug('url')->getPathInfo();
        $dispatch = $this->route->getDispatch(
            $this['method'],
            $uri
        );
        if(is_int($dispatch)){
            http_response_code($dispatch);
            trigger_error('no match router found');
            \PMVC\call_plugin(
                'dispatcher',
                'stop',
                array(true)
            );
            return;
        }
        \PMVC\set($request, $dispatch->var);
        $b = new \PMVC\MappingBuilder();
        $b->addAction('index', array(
            _FUNCTION=>$dispatch->action
        ));
        $this->addMapping($b->getMappings());
    }

    public function get($path=null,$function=null)
    {
        $this->route->addRoute('GET',$path,$function);
        return $this;
    }

    public function post($path,$function)
    {
        $this->route->addRoute('POST',$path,$function);
        return $this;
    }

    public function put($path,$function)
    {
        $this->route->addRoute('PUT',$path,$function);
        return $this;
    }

    public function delete($path,$function)
    {
        $this->route->addRoute('DELETE',$path,$function);
        return $this;
    }

}



