<?php

$url = str_replace(" ", "+",'https://api.spotify.com/v1/artists/'.$_GET['id']);

require('api/spotify/request.php');

echo sendRequest($url, $token, "GET");