<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Interfaces;

use Bro\WorldCoreBundle\Domain\Entity\Interfaces\EntityInterface;
use Throwable;

/**
 * @package Bro\WorldCoreBundle
 */
interface RestDeleteResourceInterface extends RestSmallResourceInterface
{
    /**
     * Generic method to delete specified entity from database.
     *
     * @throws Throwable
     */
    public function delete(string $id, ?bool $flush = null, ?string $entityManagerName = null): EntityInterface;
}
