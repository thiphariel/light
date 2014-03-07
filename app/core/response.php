<?php
/**
 * Handle the Controller loading in accordance with the Request object
 * @author Thomas Collot
 */

namespace Light\App\Core;

use Light\App\Kernel;
use Light\Src\Controllers;

class Response
{
    private $request;
    private $kernel;

    /**
     * Construct the Response object with the Request
     * @param Request $request
     * @param Kernel $kernel
     **/
    public function __construct(Request $request, Kernel $kernel)
    {
        $this->request = $request;
        $this->kernel = $kernel;
    }

    /**
     * Handle url parsing
     * @param $url
     **/
    private function parse($url, Request $request)
    {
        $url = explode('/', trim($request->url(), '/'));
        array_shift($url); // Permet de retirer le premier élément du tableau (Base URL)

        $request->controller = sizeof($url) && is_array($url) ? array_shift($url) : 'light';
        $request->action = sizeof($url) && is_array($url) ? array_shift($url) : 'index';
        $request->params = $url;
    }

    /**
     * Load a specific controller in accordance with the parsing and call his action (with or w/o param)
     * (need to be updated)
     */
    public function send()
    {
        $this->parse($this->request->url(), $this->request);
        $controller = $this->controller();

        if (!in_array($this->request->action, array_diff(get_class_methods($controller), get_class_methods(get_parent_class($controller))))) {
            if ($this->kernel->debug()) {
                $this->notFound('The controller <em>' . $this->request->controller . '</em> does not have the <strong>' . $this->request->action . '</strong> action');
            } else {
                $this->notFound('<p>Oups, this request cannot be completed ... I\'m not able to find this page :(</p><p><a href="javascript:history.back()">&larr; Back</a></p>');
            }
        }

        call_user_func_array(array($controller, $this->request->action), $this->request->params);
    }

    /**
     * Handle controller loading
     **/
    public function controller()
    {
        $name = '\Light\Src\Controllers\\' . ucfirst($this->request->controller);

        if (!file_exists(_src . 'controllers/' . $this->request->controller . '.php')) {
            $this->notFound('<h1>404 Not found</h1><p>Oups, this request cannot be completed ... I\'m not able to find this page :(</p><p><a href="javascript:history.back()">&larr; Back</a></p>');
        }
        
        return new $name($this->request, $this->kernel);
    }

    /**
     * Handle 404 not found
     * @param string $msg
     **/
    public function notFound($msg)
    {
        header("HTTP/1.0 404 Not Found");
        die($msg);
    }
}
