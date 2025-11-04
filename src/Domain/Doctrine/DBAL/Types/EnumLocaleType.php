<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Doctrine\DBAL\Types;

use Bro\WorldCoreBundle\Domain\Enum\Locale;

/**
 * @package Bro\WorldCoreBundle
 */
class EnumLocaleType extends EnumType
{
    protected static string $name = Types::ENUM_LOCALE;
    protected static string $enum = Locale::class;
}
