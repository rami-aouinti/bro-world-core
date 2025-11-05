<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\AbstractString;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package Bro\WorldCoreBundle
 */
trait SlugTrait
{
    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private string $slug;

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(
        string $slug
    ): self {
        $this->slug = (string)$slug;

        return $this;
    }
}
