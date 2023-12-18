<?php

namespace JQuest;

use JQuest\Models\User;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 14, 2015
 */
class Auth
{

    private static $initialized = false;

    /**
     *
     * @var User
     */
    private static $user;

    public static function init()
    {
        if (!self::$initialized) {
            if (!isset($_SESSION)) {
              session_name('jquest_contact3');
              session_cache_limiter('private_no_expire');
              session_cache_expire(0);
              session_set_cookie_params(86400);
              session_start();
            }
            self::$initialized = true;
        }
    }

    /**
     *
     * @return boolean|int
     */
    public static function check()
    {
        self::init();

        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        } else {
            return false;
        }
    }

    /**
     *
     * @return boolean|User
     */
    public static function user()
    {
        $user_id = self::check();

        if ($user_id === false) {
            return false;
        } else {
            $user = User::find($user_id);
            return !$user ? false : $user;
        }
    }

    /**
     *
     * @param string $name
     * @param string $password
     * @return boolean
     */
    public static function login($name, $password)
    {
        self::init();

        $user = User::getByNameAndPassword($name, $password);
        if ($user) {
            $_SESSION['user_id'] = (int)$user->id;
            return true;
        } else {
            return false;
        }
    }

    public static function logout()
    {
        self::init();
        \session_destroy();
        \setcookie(\session_name(), null, -1, '/');
    }
}
