<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @package Bro\WorldCoreBundle
 */
trait VisibleTrait
{
    #[ORM\Column(name: 'visible', type: 'boolean', nullable: false)]
    #[Serializer\Expose]
    #[Serializer\Groups(['Default'])]
    private bool $visible = true;

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }
}
