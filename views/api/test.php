<?php
header("Content-Type:Application/JSON");
$data = getIP();
echo json_encode([
    'original' => $data,
    'md5' => (md5($data)),
    'SHA1' => (sha1($data)),
    'SHA256' => (sha256($data)),
    'SHA512' => (sha512($data)),
    'md5(SHA1)' => md5(sha1($data)),
    'md5(SHA256)' => md5(sha256($data)),
    'md5(SHA512)' => md5(sha512($data)),
]);