<?php
namespace PMVC\PlugIn\yo;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\yo';

class yo extends \PMVC\PlugIn
{
    private $_route;

    public function init()
    {
        $this->setDefaultAlias(\PMVC\plug('controller'));
        $pEvent = \PMVC\plug('dispatcher');
        $pEvent->attach($this,'MapRequest');
        $pEvent->attach($this,'SetConfig__run_form_');
        $this->_route=\PMVC\plug('fast_route');
        $this['method'] = $this->getRequest()->getMethod();
    }

    public function onSetConfig__run_form_($subject)
    {
        $subject->detach($this);
        $method = \PMVC\value(
            \PMVC\getOption(_RUN_FORM),
            ['_method']
        );
        if (!empty($method)) {
            $this['method'] = $method;
        }
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
            http_response_code($dispatch);
            \PMVC\callPlugIn (
                'dispatcher',
                'stop',
                [true]
            );
            return !trigger_error('no match router found');
        }
        \PMVC\set($request, $dispatch->var);
        if (is_string($dispatch->action)) {
            $this->setAppAction($dispatch->action);
        } else {
            $b = new \PMVC\MappingBuilder();
            $b->addAction('index', $dispatch->action);
            $this->addMapping($b);
        }
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
