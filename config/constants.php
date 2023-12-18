<?php
define("DIR", realpath(__DIR__ . "/.."));
define('SLIM_DEBUG', getenv('SLIM_DEBUG') ? true : false);
define('SLIM_ENVIRONMENT', getenv('SLIM_ENVIRONMENT') ? getenv('SLIM_ENVIRONMENT') : 'production');
define('BASE_URL', "https://www.j-quest.jp/contact3_testing/enquiry");