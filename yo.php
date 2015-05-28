<?php
namespace PMVC\PlugIn\Yo;

${_INIT_CONFIG}[_CLASS] = 'PMVC\PlugIn\Yo\Yo';

class Yo extends \PMVC\PLUGIN
{
    private $route;

    public function init()
    {
        $controller = new \PMVC\ActionController();
        $this->setDefaultAlias($controller);
        \PMVC\plug('observer')->addObserver($this,'MapRequest');
        $this->route=\PMVC\plug('fast_route');
    }

    public function onMapRequest()
    {
        $request = $this->getRequest();
        \PMVC\plug('url')->setEnv(array(
            'REQUEST_URI',
            'SCRIPT_NAME'
        ));
        $uri = \PMVC\plug('url')->getPathInfo();
        $dispatch = $this->route->getDispatch(
            $request->getMethod(),
            $uri
        );
        if(is_int($dispatch)){
            http_response_code($dispatch);
            return;
        }
        $request->set($dispatch->var);
        $b = new \PMVC\MappingBuilder();
        $b->addAction('index', array(
            _FUNCTION=>$dispatch->action
        ));
        $this->setMapping($b->getMappings());
    }

    public function get($path,$function)
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



