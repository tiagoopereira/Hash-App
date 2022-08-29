<?php

namespace App\Service;

abstract class GenerateHashService
{
    public static function make(string $string, int $previousBlock = 0): array
    {
        if (!isset($string) || empty($string)) {
            throw new \InvalidArgumentException('No string provided');
        }

        $batch = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $attempts = 0;

        do {
            $key = GenerateKeyService::make();
            $hash = md5($key . $string);
            $attempts++;
        } while (substr($hash, 0, 4) !== '0000');

        return [
            'batch' => $batch,
            'block' => ++$previousBlock,
            'string' => $string,
            'key' => $key,
            'hash' => $hash,
            'attempts' => $attempts,
        ];
    }
}