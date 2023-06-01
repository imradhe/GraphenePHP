<?php
/**
 * GraphenePHP Router Class
 */
class Router
{
    public $request;
    public $queries;
    public $routes = [];
    public $slug;

    /**
     * Router constructor.
     *
     * @param array $request The current request information.
     */
    public function __construct(array $request)
    {
        $this->request = $request['REQUEST_URI'];
        $this->queries = explode("&", explode("?", $this->request)[1]);
        $this->routes = json_decode(file_get_contents('routes.json'), true);
    }

    /**
     * Runs the router and processes the request.
     *
     * @return mixed The response from the router.
     */
    public function run()
    {
        require('config.php');
        
        // URL parsing
        $this->request = (empty($config['APP_SLUG'])) ? substr(explode("?", $this->request)[0], 1) : substr(explode("?", $this->request)[0], strlen($config['APP_SLUG']) + 2);

        if ($this->request[strlen($this->request) - 1] == "/") {
            // Removing trailing slashes
            return header("Location:" . home() . substr($this->request, 0, strlen($this->request) - 1));
        } else {
            if (empty(trim($this->request))) {
                // If empty route, return home page
                return include('views/index.php');
            } elseif (!empty($this->routes[$this->request])) {
                // Return the specific file for the route
                $routes = $this->routes;
                return include($routes[$this->request]['path']);
            } elseif (!empty($this->routes[substr($this->request, 0, strripos($this->request, '/'))]) && !empty($this->routes[substr($this->request, 0, strripos($this->request, '/'))]['pattern'])) {
                $this->slug = substr($this->request, strripos($this->request, '/') + 1, strlen($this->request));
                $this->request = substr($this->request, 0, strripos($this->request, '/'));
                $_REQUEST['slug'] = $this->slug;
                $routes = $this->routes;
                if (preg_match($routes[$this->request]['pattern'], $_REQUEST['slug'])) {
                    return include($routes[$this->request]['path']);
                } else {
                    return pageNotFound();
                }
            } else {
                return pageNotFound();
            }
        }
    }
}
