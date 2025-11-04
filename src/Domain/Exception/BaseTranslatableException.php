<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Exception;

use Bro\WorldCoreBundle\Domain\Exception\Interfaces\TranslatableExceptionInterface;

/**
 * @package Bro\WorldCoreBundle
 */
abstract class BaseTranslatableException extends BaseException implements TranslatableExceptionInterface
{
    public function getParameters(): array
    {
        return [];
    }

    public function getDomain(): ?string
    {
        return null;
    }
}
