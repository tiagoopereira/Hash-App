<?php

namespace App\Service;

abstract class KeyService
{
    const DEFAULT_KEY_LENGTH = 8;
    const DEFAULT_KEY_CHARSET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public static function generate(int $length = self::DEFAULT_KEY_LENGTH): string
    {
        if ($length < 1) {
            throw new \InvalidArgumentException('Key length must be greater than 0');
        }

        $key = '';
        $charset = self::DEFAULT_KEY_CHARSET;

        for ($i = 0; $i < $length; $i++) {
            $key .= $charset[random_int(0, strlen($charset) - 1)];
        }

        return $key;
    }
}