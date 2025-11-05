<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @package Bro\WorldCoreBundle
 */
trait ActiveTrait
{
    #[ORM\Column(name: 'active', type: 'boolean', nullable: false)]
    private bool $active = true;

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
