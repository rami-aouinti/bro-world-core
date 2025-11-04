<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest;

use Bro\WorldCoreBundle\Application\Rest\Interfaces\RestSmallResourceInterface;
use Bro\WorldCoreBundle\Application\Rest\Traits\RestResourceBaseMethods;
use Bro\WorldCoreBundle\Domain\Repository\Interfaces\BaseRepositoryInterface;

/**
 * @package Bro\WorldCoreBundle
 */
abstract class RestSmallResource implements RestSmallResourceInterface
{
    use RestResourceBaseMethods;

    public function __construct(
        protected readonly BaseRepositoryInterface $repository,
    ) {
    }
}
