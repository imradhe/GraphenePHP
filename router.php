<?php
class Router
{
    public $request;
    public $queries;
    public $routes = [];
    public $slug;

    public function __construct(array $request)
    {
        $this->request = $request['REQUEST_URI'];
        $this->queries = explode("&", explode("?", $this->request)[1]);
        $this->routes = json_decode(file_get_contents('routes.json'), true);
    }

    public function run()
    {
        require('config.php');
        $this->request = (empty($config['APP_SLUG'])) ? substr(explode("?", $this->request)[0], 1) : substr(explode("?", $this->request)[0], strlen($config['APP_SLUG']) + 2);

        if ($this->request[strlen($this->request) - 1] == "/") {
            return header("Location:" . home() . substr($this->request, 0, strlen($this->request) - 1));
        } else {
            if (empty(trim($this->request))) {
                return include('views/index.php');
            } else {
                $routes = $this->routes;
                $routeFound = false;
                foreach ($routes as $route => $data) {
                    if (preg_match($this->getPattern($route), $this->request, $matches)) {
                        $this->setRouteVariables($data, $matches);
                        include($data['path']);
                        $routeFound = true;
                        break;
                    }
                }
                if (!$routeFound) {
                    return pageNotFound();
                }
            }
        }
    }

    private function getPattern($route)
    {
        $pattern = str_replace('/', '\/', $route);
        $pattern = preg_replace('/{([\w-]+)}/', '(?P<$1>[\w-]+)', $pattern);
        return '/^' . $pattern . '$/';
    }
    

    private function setRouteVariables(&$data, $matches)
    {
        foreach ($matches as $key => $value) {
            if (is_string($key) && !empty($value)) {
                $_REQUEST[$key] = $value;
            }
        }
    }
    

}
