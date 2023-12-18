<?php

namespace JQuest;

class Config
{
    public static function load($name)
    {
        if (SLIM_ENVIRONMENT === 'production') {
            return include DIR . '/config/' . $name . '.php';
        } else {
            return include DIR . '/config/' . SLIM_ENVIRONMENT . '/' . $name . '.php';
        }
    }
}