<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\DTO\Interfaces\RestDtoInterface;
use Bro\WorldCoreBundle\Application\Rest\Traits\RestResourcePatch as PatchLifeCycle;
use Bro\WorldCoreBundle\Domain\Entity\Interfaces\EntityInterface;

/**
 * @package Bro\WorldCoreBundle
 */
trait ResourcePatchMethod
{
    use PatchLifeCycle;

    /**
     * {@inheritdoc}
     */
    public function patch(
        string $id,
        RestDtoInterface $dto,
        ?bool $flush = null,
        ?bool $skipValidation = null,
        ?string $entityManagerName = null
    ): EntityInterface {
        $flush ??= true;
        $skipValidation ??= false;
        // Fetch entity
        $entity = $this->getEntity($id, $entityManagerName);
        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, $dto::class, $dto, true, $entityManagerName);
        // Before callback method call
        $this->beforePatch($id, $restDto, $entity);
        // Validate DTO
        $this->validateDto($restDto, $skipValidation);
        // Create or update entity
        $this->persistEntity($entity, $restDto, $flush, $skipValidation, $entityManagerName);
        // After callback method call
        $this->afterPatch($id, $restDto, $entity);

        return $entity;
    }
}
