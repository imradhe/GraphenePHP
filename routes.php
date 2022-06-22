<?php
require('router.php');

        $router = new Router($_SERVER);


        $router->addRoute('', 'views/index.php');
        

        $router->addRoute('test','views/test.php');
        $router->addRoute('lyrics','views/lyrics.php');
        $router->addRoute('subtitles','views/subtitles.php');
            
        
        /* Auth Routes */
            
        $router->addRoute('login','views/auth/login.php');
        $router->addRoute('register','views/auth/register.php');
        $router->addRoute('logout','views/auth/logout.php');

        /*API Routes*/  
        $router->addRoute('api/example','api/example.php');
        $router->addRoute('api/logs','api/logs.php');
        $router->addRoute('api/spotify/search','api/spotify/search.php');
        $router->addRoute('api/spotify/tracks','api/spotify/tracks.php');
        $router->addRoute('api/spotify/albums','api/spotify/albums.php');
        $router->addRoute('api/spotify/artists','api/spotify/artists.php');
        $router->addRoute('api/test','api/test.php');
        $router->addRoute('api/login','api/auth/login.php');
        $router->addRoute('api/logout','api/auth/logout.php');
        

        

        /* Run Routes */
        $router->run();

        