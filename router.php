<?php
/**
 * -----------------------------------------------------------------------------
 * Router Class
 * -----------------------------------------------------------------------------
 *
 * The Router class handles the routing system of the GraphenePHP application.
 * It processes incoming requests and determines which file or action to execute.
 *
 * Properties:
 * - $request: Holds the current request URI.
 * - $queries: Holds the query parameters extracted from the request URI.
 * - $routes: Holds the routes defined in the routes.json file.
 * - $slug: Holds the slug extracted from the request URI.
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
     * @param array $request The request array containing the REQUEST_URI.
     */
    public function __construct(array $request)
    {
        $this->request = $request['REQUEST_URI'];
        $this->queries = explode("&",(explode("?",$this->request)[1])?? '');
        $this->routes = json_decode(file_get_contents('routes.json'), true);       
    }

    /**
     * Run the router.
     *
     * This method processes the incoming request and determines the appropriate
     * action to take based on the requested route.
     */
    public function run()
    {
        require('config.php');

        // Parse the URL
        $this->request = (empty($config['APP_SLUG'])) ? substr(explode("?", $this->request)[0], 1) : substr(explode("?", $this->request)[0], strlen($config['APP_SLUG']) + 2);

        if (empty(trim($this->request))) {
            // If the route is empty, return the home page
            return include('views/index.php');
        } elseif ($this->request !== '/' && $this->request[(strlen($this->request) - 1)] === "/") {
            // Remove trailing slashes
            return header("Location:" . home() . substr($this->request, 0, strlen($this->request) - 1));
        } else {
            if (!empty($this->routes[$this->request])) {
                // Return the file associated with the requested route
                $routes = $this->routes;
                return include($routes[$this->request]['path']);
            } elseif (!empty($this->routes[substr($this->request, 0, strripos($this->request, '/'))]) && !empty($this->routes[substr($this->request, 0, strripos($this->request, '/'))]['pattern'])) {
                // Extract the slug from the request and check against the defined pattern
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
