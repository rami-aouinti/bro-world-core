<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Entity\Interfaces;

use DateTimeImmutable;

/**
 * @package Bro\WorldCoreBundle
 */
interface EntityInterface
{
    /**
     * @return non-empty-string
     */
    public function getId(): string;
    public function getCreatedAt(): ?DateTimeImmutable;
}
