<?php

/**
 * Class Auth
 */
class Auth
{
    const IS_LOGGED = 'auth__is_logged';
    const IP        = 'auth__ip';

    /**
     * @return bool
     */
    static function check()
    {
        return (
            Session::safeGet(self::IS_LOGGED) != NULL &&
            Session::safeGet(self::IP) == Input::server('REMOTE_ADDR')
        );
    }

    /**
     * @param $login
     * @param $password
     * @return bool
     */
    static function login($login, $password)
    {
        if ($login == ADMIN_USER && $password == ADMIN_PASS) {
            Session::safeSet(self::IS_LOGGED, TRUE);
            Session::safeSet(self::IP, Input::server('REMOTE_ADDR'));
            return TRUE;
        }

        return FALSE;
    }

    static function logout()
    {
        Session::safeUnset(self::IS_LOGGED);
        Session::safeUnset(self::IP);
    }
}