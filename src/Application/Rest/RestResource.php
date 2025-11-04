<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest;

use Bro\WorldCoreBundle\Application\Rest\Interfaces\RestResourceInterface;
use Bro\WorldCoreBundle\Application\Rest\Traits\Methods;
use Bro\WorldCoreBundle\Domain\Repository\Interfaces\BaseRepositoryInterface;

/**
 * @package Bro\WorldCoreBundle
 */
abstract class RestResource implements RestResourceInterface
{
    use Traits\RestResourceBaseMethods;
    use Methods\ResourceCountMethod;
    use Methods\ResourceCreateMethod;
    use Methods\ResourceDeleteMethod;
    use Methods\ResourceFindMethod;
    use Methods\ResourceFindOneByMethod;
    use Methods\ResourceFindOneMethod;
    use Methods\ResourceIdsMethod;
    use Methods\ResourcePatchMethod;
    use Methods\ResourceSaveMethod;
    use Methods\ResourceUpdateMethod;

    public function __construct(
        protected readonly BaseRepositoryInterface $repository,
    ) {
    }
}
