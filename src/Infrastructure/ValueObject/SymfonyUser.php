<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Infrastructure\ValueObject;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SymfonyUser
 *
 * @package Bro\WorldCoreBundle\Infrastructure\ValueObject
 * @author  Rami Aouinti <rami.aouinti@gmail.com>
 */
final readonly class SymfonyUser implements UserInterface
{
    public function __construct(
        private ?string $userIdentifier,
        private ?string $id,
        private ?string $fullName,
        private ?string $avatar,
        private ?array $roles
    )
    {
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

}
