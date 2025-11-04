<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Exception\Interfaces;

use Throwable;

/**
 * @package Bro\WorldCoreBundle
 */
interface TranslatableExceptionInterface extends Throwable
{
    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array;

    public function getDomain(): ?string;
}
