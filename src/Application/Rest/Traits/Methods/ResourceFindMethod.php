<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\Rest\Traits\RestResourceFind as FindLifeCycle;
use Bro\WorldCoreBundle\Domain\Entity\Interfaces\EntityInterface;

/**
 * @package Bro\WorldCoreBundle
 */
trait ResourceFindMethod
{
    use FindLifeCycle;

    /**
     * {@inheritdoc}
     *
     * @return array<int, EntityInterface>
     */
    public function find(
        ?array $criteria = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        ?array $search = null,
        ?string $entityManagerName = null
    ): array {
        $criteria ??= [];
        $orderBy ??= [];
        $search ??= [];
        // Before callback method call
        $this->beforeFind($criteria, $orderBy, $limit, $offset, $search);
        // Fetch data
        $entities = $this->getRepository()->findByAdvanced(
            $criteria,
            $orderBy,
            $limit,
            $offset,
            $search,
            $entityManagerName
        );
        // After callback method call
        $this->afterFind($criteria, $orderBy, $limit, $offset, $search, $entities);

        return $entities;
    }
}
