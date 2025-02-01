<?php

    namespace Lunaris\Security\Facades;

    class Password
    {
        public static function check($password)
        {
            $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,32}$/';

            if (!preg_match($pattern, $password)) {
                return false;
            }

            return true;
        }

        public static function hash($password)
        {
            $password = password_hash($password, PASSWORD_BCRYPT);
            return $password;
        }

        public static function verify($password, $hash)
        {
            return password_verify($password, $hash);
        }
    }