<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Interfaces;

use Bro\WorldCoreBundle\Application\DTO\Interfaces\RestDtoInterface;
use Bro\WorldCoreBundle\Domain\Entity\Interfaces\EntityInterface;
use Throwable;

/**
 * @package Bro\WorldCoreBundle
 */
interface RestCreateResourceInterface extends RestSaveResourceInterface
{
    /**
     * Generic method to create new item (entity) to specified database repository. Return value is created entity for
     * specified repository.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @throws Throwable
     */
    public function create(
        RestDtoInterface $dto,
        ?bool $flush = null,
        ?bool $skipValidation = null,
        ?string $entityManagerName = null
    ): EntityInterface;
}
