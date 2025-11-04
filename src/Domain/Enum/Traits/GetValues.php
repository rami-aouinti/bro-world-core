<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Enum\Traits;

use function array_column;

/**
 * @package Bro\WorldCoreBundle
 */
trait GetValues
{
    /**
     * @return array<int, string>
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
