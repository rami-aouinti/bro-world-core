<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Service;

use Bro\WorldCoreBundle\Domain\ValueObject\UserId;
use Bro\WorldCoreBundle\Infrastructure\ValueObject\SymfonyUser;

/**
 * AuthenticatorServiceInterface
 */
interface AuthenticatorServiceInterface
{
    public function getUserId(): ?UserId;

    public function getToken(string $id): ?string;

    public function getSymfonyUser(): ?SymfonyUser;
}
