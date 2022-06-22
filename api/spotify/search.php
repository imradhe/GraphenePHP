<?php

$url = str_replace(" ", "+",'https://api.spotify.com/v1/search?q='.$_GET['q'].'&type='.$_GET['type']);

require('api/spotify/request.php');

echo sendRequest($url, $token, "GET");