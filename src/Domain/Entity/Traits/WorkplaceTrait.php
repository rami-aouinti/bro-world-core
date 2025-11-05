<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package Bro\WorldCoreBundle
 */
trait WorkplaceTrait
{
    #[ORM\Column(type: 'uuid', nullable: true)]
    #[Assert\NotNull]
    private ?UuidInterface $workplaceId = null;

    public function getWorkplaceId(): ?UuidInterface
    {
        return $this->workplaceId;
    }

    public function setWorkplaceId(?UuidInterface $workplaceId): void
    {
        $this->workplaceId = $workplaceId;
    }
}
