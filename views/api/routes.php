<?php
$data = json_decode(file_get_contents('routes.json'));



if (!empty($data)) {
    $res['status'] = '200';
    $res['message'] = 'Success';
    $res['data'] = $data;
} else {
    $res['status'] = '500';
    $res['message'] = 'No Routes';
    $res['data'] = $data;
}


jsonResponse($res, $res['status']);