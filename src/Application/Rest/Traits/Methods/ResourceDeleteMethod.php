<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\Rest\Traits\RestResourceDelete as DeleteLifeCycle;
use Bro\WorldCoreBundle\Domain\Entity\Interfaces\EntityInterface;

/**
 * @package Bro\WorldCoreBundle
 */
trait ResourceDeleteMethod
{
    use DeleteLifeCycle;

    /**
     * {@inheritdoc}
     */
    public function delete(string $id, ?bool $flush = null, ?string $entityManagerName = null): EntityInterface
    {
        $flush ??= true;
        // Fetch entity
        $entity = $this->getEntity($id, $entityManagerName);
        // Before callback method call
        $this->beforeDelete($id, $entity);
        // And remove entity from repo
        $this->getRepository()->remove($entity, $flush, $entityManagerName);
        // After callback method call
        $this->afterDelete($id, $entity);

        return $entity;
    }
}
