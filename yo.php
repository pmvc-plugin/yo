<?php
namespace PMVC\PlugIn\yo;

${_INIT_CONFIG}[_CLASS] = 'PMVC\PlugIn\yo\yo';

class yo extends \PMVC\PLUGIN
{
    private $_route;

    public function init()
    {
        $controller = \PMVC\getC(); 
        if (!$controller) {
            $controller = new \PMVC\ActionController();
        }
        $this->setDefaultAlias($controller);
        \PMVC\plug('dispatcher')->attach($this,'MapRequest');
        $this->_route=\PMVC\plug('fast_route');
        \PMVC\plug('url')->setEnv(array(
            'REQUEST_URI',
            'SCRIPT_NAME'
        ));
        $this['method'] = $this->getRequest()->getMethod();
    }

    public function getRoutes()
    {
        return $this->_route->getRoutes();
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
        $this->_route->addRoute('GET',$path,$function);
        return $this['this'];
    }

    public function post($path,$function)
    {
        $this->_route->addRoute('POST',$path,$function);
        return $this['this'];
    }

    public function put($path,$function)
    {
        $this->_route->addRoute('PUT',$path,$function);
        return $this['this'];
    }

    public function delete($path,$function)
    {
        $this->_route->addRoute('DELETE',$path,$function);
        return $this['this'];
    }

}

