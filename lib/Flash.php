<?php

namespace JQuest;

use JQuest\Log;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 31, 2015
 */
class Flash
{

    const KEY = 'flash';
    const SUCCESS = 'success';
    const ERROR = 'error';

    private static function init()
    {
        if (!isset($_SESSION)) {
            sesion_start();
        }

        if (!isset($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }
    }

    /**
     *
     * @param string $status
     * @param string $message
     */
    public static function set($status, $message)
    {
        self::init();

        $_SESSION[self::KEY][] = new FlashMessage($status, $message);
    }

    public static function setSuccess($message)
    {
        self::set(self::SUCCESS, $message);
    }

    public static function setError($message)
    {
        self::set(self::ERROR, $message);
    }

    /**
     *
     * @return array
     */
    public static function get()
    {
        self::init();

        if (isset($_SESSION[self::KEY])) {
            $messages = $_SESSION[self::KEY];
            unset($_SESSION[self::KEY]);
            return $messages;
        }

        return [];
    }
}