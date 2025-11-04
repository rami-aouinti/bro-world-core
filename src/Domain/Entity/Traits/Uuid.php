<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Entity\Traits;

use Bro\WorldCoreBundle\Domain\Rest\UuidHelper;
use Ramsey\Uuid\UuidInterface;
use Throwable;

/**
 * @package Bro\WorldCoreBundle
 */
trait Uuid
{
    public function getUuid(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @throws Throwable
     */
    protected function createUuid(): UuidInterface
    {
        return UuidHelper::getFactory()->uuid1();
    }
}
