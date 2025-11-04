<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Enum\Interfaces;

use BackedEnum;

/**
 * Enum StringEnumInterface
 *
 * @package Bro\WorldCoreBundle
 */
interface StringEnumInterface extends BackedEnum
{
    /**
     * @return array<int, string>
     */
    public static function getValues(): array;
}
