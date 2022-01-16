<?php

namespace App\Factory;

use App\Entity\Hash;
use App\Exceptions\ValidationException;

abstract class HashEntityFactory
{
    const REQUIRED_FIELDS = ['batch', 'block', 'string', 'key', 'hash', 'attempts'];

    public static function create(array $data): Hash
    {
        self::validate($data);

        $hash = new Hash();
        $hash->setBatch($data['batch'])
             ->setBlock($data['block'])
             ->setString($data['string'])
             ->setKey($data['key'])
             ->setGeneratedHash($data['hash'])
             ->setAttempts($data['attempts']);

        return $hash;
    }

    private static function validate(array $data): void
    {
        foreach (self::REQUIRED_FIELDS as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new ValidationException("The field \"{$field}\" is required", 422);
            }
        }
    }
}