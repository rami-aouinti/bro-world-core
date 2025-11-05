<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\DTO\Interfaces;

use Bro\WorldCoreBundle\Infrastructure\ValueObject\SymfonyUser;

/**
 * @package Bro\WorldCoreBundle\Application\DTO\Interfaces
 * @author  Rami Aouinti <rami.aouinti@gmail.com>
 */
interface SymfonyUserAwareDtoInterface
{
    public function applySymfonyUser(SymfonyUser $symfonyUser): void;
}
