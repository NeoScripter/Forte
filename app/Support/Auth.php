<?php

declare(strict_types=1);

namespace Support;

use DB\SQL\Mapper;
use InvalidArgumentException;

final class Auth extends \Prefab
{
    private ?array $user;

    function __construct()
    {
        $session_user = \Base::instance()->get('SESSION.user');
        $this->user = $session_user
            ? ['name' => $session_user['name'], 'email' => $session_user['email']]
            : null;
    }

    public static function user(): array|null
    {
        return self::instance()->user;
    }

    public static function set_user(array|Mapper $values): void
    {
        $data = $values instanceof Mapper
            ? ['name' => $values->name, 'email' => $values->email]
            : $values;

        if (empty($data['name']) || empty($data['email'])) {
            throw new InvalidArgumentException('User must have a name and email');
        }

        \Base::instance()->set('SESSION.user', $data);
        self::instance()->user = $data;
    }

    public static function check(): bool
    {
        return self::instance()->user != null;
    }

    public static function clear(): void
    {
        \Base::instance()->clear('SESSION.user');
        self::instance()->user = null;
    }
}
