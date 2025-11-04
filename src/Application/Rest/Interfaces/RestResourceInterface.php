<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Rest\Interfaces;

/**
 * @package Bro\WorldCoreBundle
 */
interface RestResourceInterface extends
    BaseRestResourceInterface,
    RestCountResourceInterface,
    RestCreateResourceInterface,
    RestDeleteResourceInterface,
    RestIdsResourceInterface,
    RestListResourceInterface,
    RestPatchResourceInterface,
    RestUpdateResourceInterface,
    RestFindOneResourceInterface
{
}
