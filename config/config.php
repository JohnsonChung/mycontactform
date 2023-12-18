<?php

use JQuest\Config;

return array(
    'database' => Config::load('database'),
    'hipchat' => Config::load('hipchat'),
    'slack' => Config::load('slack'),
    'mail' => Config::load('mail'),
    'superadmin' => 1
);
