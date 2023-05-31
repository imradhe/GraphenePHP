<?php
locked(['admin']);

DB::connect();
$data = DB::select('logs')->fetchAll();
DB::close();

foreach ($data as $key => $value) {
    $res['data'][$key] = $value;
}


if (!empty($res['data'])) {
    $res['status'] = '200';
    $res['message'] = 'Success';
    $res['count'] = count($res['data']);
} else {
    $res['status'] = '500';
    $res['message'] = mysqli_error($con);
    $res['data'] = $data[0][0];
}

http_response_code($res['status']);
header('Content-Type: application/json');
echo json_encode(array_reverse($res));