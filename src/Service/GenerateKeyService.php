<?php

namespace App\Service;

abstract class GenerateKeyService
{
    const DEFAULT_KEY_LENGTH = 8;
    const DEFAULT_KEY_CHARSET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public static function make(int $length = self::DEFAULT_KEY_LENGTH): string
    {
        if ($length < 1) {
            throw new \InvalidArgumentException('Key length must be greater than 0');
        }

        $key = '';

        for ($i = 0; $i < $length; $i++) {
            $key .= self::DEFAULT_KEY_CHARSET[random_int(0, strlen(self::DEFAULT_KEY_CHARSET) - 1)];
        }

        return $key;
    }
}