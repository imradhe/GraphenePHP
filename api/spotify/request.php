<?php
$token = 'BQDoBvbp0O632BSMj76pEXc1t7CLnAPKDllyVn8zcH5gspewei2YUMFw8oq8hldTO0N0Us7xqUV5JG_ApE90L6bNSbrSgRtWETHhUzglbQB-PWhkYEDOyaHzQp7Hhk8sCUiDmX9yDIsL9ywEPKpC9SZauSQtldUcjj3XMnAUjTHgA2qm14dfyUCPp-AR1qK9qWDc5EDcvl5u_vL2ZvgT_9VMAsoKplw_F3nWIac8d3qilQ';

function sendRequest($url, $token, $method){
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => $method,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;
}