<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\Rest\Traits\RestResourceFindOne as FindOneLifeCycle;
use Bro\WorldCoreBundle\Domain\Entity\Interfaces\EntityInterface;

/**
 * @package Bro\WorldCoreBundle
 */
trait ResourceFindOneMethod
{
    use FindOneLifeCycle;

    /**
     * {@inheritdoc}
     */
    public function findOne(
        string $id,
        ?bool $throwExceptionIfNotFound = null,
        ?string $entityManagerName = null
    ): ?EntityInterface {
        $throwExceptionIfNotFound ??= false;
        // Before callback method call
        $this->beforeFindOne($id);
        /** @var EntityInterface|null $entity */
        $entity = $this->getRepository()->findAdvanced(id: $id, entityManagerName: $entityManagerName);
        $this->checkThatEntityExists($throwExceptionIfNotFound, $entity);
        // After callback method call
        $this->afterFindOne($id, $entity);

        return $entity;
    }
}
