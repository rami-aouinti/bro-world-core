<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Doctrine\DBAL\Types;

use Bro\WorldCoreBundle\Domain\Enum\Language;

/**
 * @package Bro\WorldCoreBundle
 */
class EnumLanguageType extends EnumType
{
    protected static string $name = Types::ENUM_LANGUAGE;
    protected static string $enum = Language::class;
}
