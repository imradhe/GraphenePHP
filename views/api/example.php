<?php
$url = 'https://jsonplaceholder.typicode.com/posts';
$data = json_decode(file_get_contents($url));

if (!empty($data)) {
    $res['status'] = '200';
    $res['message'] = 'Success';
    $res['data'] = $data;
} else {
    $res['status'] = '500';
    $res['message'] = "No Data";
    $res['data'] = $data;
}

http_response_code($res['status']);
header('Content-Type: application/json');
echo json_encode($res);