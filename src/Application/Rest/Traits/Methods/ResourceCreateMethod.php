<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\DTO\Interfaces\RestDtoInterface;
use Bro\WorldCoreBundle\Application\Rest\Traits\RestResourceCreate as CreateLifeCycle;
use Bro\WorldCoreBundle\Domain\Entity\Interfaces\EntityInterface;
use Throwable;
use UnexpectedValueException;

use function assert;

/**
 * @package Bro\WorldCoreBundle
 */
trait ResourceCreateMethod
{
    use CreateLifeCycle;

    /**
     * {@inheritdoc}
     */
    public function create(
        RestDtoInterface $dto,
        ?bool $flush = null,
        ?bool $skipValidation = null,
        ?string $entityManagerName = null
    ): EntityInterface {
        $flush ??= true;
        $skipValidation ??= false;
        // Create new entity
        $entity = $this->createEntity();
        // Before callback method call
        $this->beforeCreate($dto, $entity);
        // Validate DTO
        $this->validateDto($dto, $skipValidation);
        // Create or update entity
        $this->persistEntity($entity, $dto, $flush, $skipValidation, $entityManagerName);
        // After callback method call
        $this->afterCreate($dto, $entity);

        return $entity;
    }

    /**
     * @throws Throwable
     */
    private function createEntity(): EntityInterface
    {
        /** @var class-string $entityClass */
        $entityClass = $this->getRepository()->getEntityName();

        $entity = new $entityClass();

        $exception = new UnexpectedValueException(
            sprintf('Given `%s` class does not implement `EntityInterface`', $entityClass),
        );

        return assert($entity instanceof EntityInterface) ? $entity : throw $exception;
    }
}
