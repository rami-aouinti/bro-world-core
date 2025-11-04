<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\Rest\Traits\RestResourceCount as CountLifeCycle;

/**
 * @package Bro\WorldCoreBundle
 */
trait ResourceCountMethod
{
    use CountLifeCycle;

    /**
     * {@inheritdoc}
     */
    public function count(?array $criteria = null, ?array $search = null, ?string $entityManagerName = null): int
    {
        $criteria ??= [];
        $search ??= [];
        // Before callback method call
        $this->beforeCount($criteria, $search);
        $count = $this->getRepository()->countAdvanced($criteria, $search, $entityManagerName);
        // After callback method call
        $this->afterCount($criteria, $search, $count);

        return $count;
    }
}
