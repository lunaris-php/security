<?php

    namespace Lunaris\Security\Facades;

    use Defuse\Crypto\Key;
    use Defuse\Crypto\Crypto;
    use InvalidArgumentException;

    class Crypt {
        private static ?Key $cachedKey = null;

        private static function key(): Key {
            if(self::$cachedKey === null) {
                $root = getcwd();
                $config = require $root . "/app/config/app.php";
                $keyAscii = $config["app_key"] ?? '';

                if(empty($keyAscii)) {
                    throw new InvalidArgumentException('APP_KEY is not set in the environment file.');
                }

                self::$cachedKey = Key::loadFromAsciiSafeString($keyAscii);
            }

            return self::$cachedKey;
        }

        public static function encrypt($string) {
            $key = self::key();
            $ciphertext = Crypto::encrypt($string, $key);
            return $ciphertext;
        }

        public static function decrypt($ciphertext) {
            $key = self::key();
            $secret_data = Crypto::decrypt($ciphertext, $key);
            return $secret_data;
        }
    }