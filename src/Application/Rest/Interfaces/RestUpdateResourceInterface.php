<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Interfaces;

use Bro\WorldCoreBundle\Application\DTO\Interfaces\RestDtoInterface;
use Bro\WorldCoreBundle\Domain\Entity\Interfaces\EntityInterface;
use Throwable;

/**
 * @package Bro\WorldCoreBundle
 */
interface RestUpdateResourceInterface extends RestSaveResourceInterface
{
    /**
     * Generic method to update specified entity with new data.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @throws Throwable
     */
    public function update(
        string $id,
        RestDtoInterface $dto,
        ?bool $flush = null,
        ?bool $skipValidation = null,
        ?string $entityManagerName = null
    ): EntityInterface;
}
