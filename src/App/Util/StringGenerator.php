<?php

namespace App\Util;

class StringGenerator
{
    public static function generate(array $parts, $length = 10)
    {
        return substr(base_convert(sha1(implode(':', $parts)), 16, 36), 0, $length );
    }

    public static function generateRandom($length = 10)
    {
        return substr(base_convert(sha1(uniqid(microtime(true), true)), 16, 36), 0, $length);
    }

    public static function generateInt(array $parts)
    {
        return self::crc64(implode(':', $parts));
    }

    public static function crc64($val)
    {
        $crc64 = ('0x' . hash('crc32', $val) . substr(hash('crc32b', $val), 0, 31));

        return $crc64 + 0;
    }
}
