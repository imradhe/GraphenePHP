<?php
class Router
{
    public $request;
    public $queries;
    public $routes = [];

    public function __construct(array $request)
    {
        $this->request = $request['REQUEST_URI'];
        $queries = explode("&",explode("?",$this->request)[1]);
        $this->queries = explode("?",$this->request)[1];
        // foreach($queries as $query){
        //     $_GET[explode("=", $query)[0]] = explode("=", $query)[1];
        // }
    }


    public function addRoute(string $uri, string $path) : void
    { 
        $uri = explode('?',$uri)[0];
        $this->routes[$uri] = $path;
    }

    
    public function hasRoute(string $uri) : bool
    {
        return array_key_exists($uri, $this->routes);
    }

   
    public function run()
    {   
       require('config.php');
       $this->request = (empty($config['APP_SLUG']))? substr(explode("?", $this->request)[0], 1): substr(explode("?", $this->request)[0], strlen($config['APP_SLUG'])+2);

        if (empty(trim($this->request))) {
          include('views/index.php');
        }
        
        elseif($this->hasRoute($this->request)){
                $routes = $this->routes;
                include($routes[$this->request]);
        }

        else {
            $uri = explode("?", $this->request)[0];
            if($uri[strlen($uri)-1] == "/") {                
                header("Location:".home().substr($uri, 0, strlen($uri)-1));
            }else{
                http_response_code(404);
                header("HTTP/1.0 404 Page Not Found");
                include('views/errors/404.php');
            }
            
        }

    }

}