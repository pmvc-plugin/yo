<?php
namespace PMVC\PlugIn\yo;

use UnexpectedValueException;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\yo';

class yo extends \PMVC\PlugIn
{
    private $_route;

    public function init()
    {
        $this->setDefaultAlias(\PMVC\plug('controller'));
        $pEvent = \PMVC\plug('dispatcher');
        $pEvent->attach($this,'MapRequest');
        $this->_route=\PMVC\plug('fast_route');
        $this['method'] = $this->getRequest()->getMethod();
    }

    public function onMapRequest()
    {
        $request = $this->getRequest();
        $uri = \PMVC\plug('url')->getPath();
        $dispatch = $this->_route->getDispatch(
            $this['method'],
            $uri
        );
        if(is_int($dispatch)){
            \PMVC\option(
                'set',
                'httpResponseCode',
                $dispatch
            );
            throw new UnexpectedValueException(json_encode([
                'error'  => 'No match router path found.',
                'method' => $this['method'],
                'uri'    => $uri
            ]));
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

    public function addRoute($method, $args)
    {
        array_unshift($args, $method);
        call_user_func_array([$this->_route,'addRoute'], $args);
        return;
    }

    public function get($path, $action)
    {
        return $this->addRoute('GET', func_get_args());
    }

    public function post($path, $action)
    {
        return $this->addRoute('POST', func_get_args());
    }

    public function put($path, $action)
    {
        return $this->addRoute('PUT', func_get_args());
    }

    public function delete($path, $action)
    {
        return $this->addRoute('DELETE', func_get_args());
    }

    public function options($path, $action)
    {
        return $this->addRoute('OPTIONS', func_get_args());
    }
}
