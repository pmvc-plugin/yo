<?php
namespace PMVC\PlugIn\yo;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\yo';

class yo extends \PMVC\PlugIn
{
    private $_route;

    public function init()
    {
        $this->setDefaultAlias(\PMVC\plug('controller'));
        \PMVC\plug('dispatcher')->attach($this,'MapRequest');
        $this->_route=\PMVC\plug('fast_route');
        \PMVC\plug('url')->setEnv(array(
            'REQUEST_URI',
            'SCRIPT_NAME'
        ));
        $this['method'] = $this->getRequest()->getMethod();
    }

    public function onMapRequest()
    {
        $request = $this->getRequest();
        $uri = \PMVC\plug('url')->getPathInfo();
        $dispatch = $this->_route->getDispatch(
            $this['method'],
            $uri
        );
        if(is_int($dispatch)){
            http_response_code($dispatch);
            trigger_error('no match router found');
            \PMVC\callPlugIn(
                'dispatcher',
                'stop',
                array(true)
            );
            return;
        }
        \PMVC\set($request, $dispatch->var);
        $b = new \PMVC\MappingBuilder();
        $b->addAction('index', $dispatch->action);
        $this->addMapping($b);
    }

    public function getRoutes()
    {
        return $this->_route->getRoutes();
    }

    public function addRoute($method, $path, $function, $params)
    {
        $params[_FUNCTION] = $function;
        $this->_route->addRoute($method,$path,$params);
    }

    public function get($path, $function, $params=array())
    {
        $this->addRoute('GET', $path, $function, $params);
        return $this['this'];
    }

    public function post($path, $function, $params=array())
    {
        $this->addRoute('POST', $path, $function, $params);
        return $this['this'];
    }

    public function put($path, $function, $params=array())
    {
        $this->addRoute('PUT', $path, $function, $params);
        return $this['this'];
    }

    public function delete($path, $function, $params=array())
    {
        $this->addRoute('DELETE', $path, $function, $params);
        return $this['this'];
    }

    public function options($path, $function, $params=array())
    {
        $this->addRoute('OPTIONS', $path, $function, $params);
        return $this['this'];
    }

}

