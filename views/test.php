<?php

DB::connect();
$logs = DB::select('logs')->fetchAll();
DB::close();

echo json_encode($logs);