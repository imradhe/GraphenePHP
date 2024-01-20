<?php
header('x-powered-by: none');
header('server-version: none');
header('x-xss-protection: true');
header('x-content-type-options: nosniff');
header("strict-transport-security: max-age=600");
header("strict-transport-security: max-age=63072000; includeSubDomains; preload");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: DENY");
header("X-Frame-Options: SAMEORIGIN");