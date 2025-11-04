<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Enum;

use Bro\WorldCoreBundle\Domain\Enum\Interfaces\DatabaseEnumInterface;
use Bro\WorldCoreBundle\Domain\Enum\Traits\GetValues;

/**
 * Language
 *
 * @package Bro\WorldCoreBundle
 */
enum Language: string implements DatabaseEnumInterface
{
    use GetValues;

    case EN = 'en';
    case RU = 'ru';
    case UA = 'ua';
    case FI = 'fi';

    public static function getDefault(): self
    {
        return self::EN;
    }
}
