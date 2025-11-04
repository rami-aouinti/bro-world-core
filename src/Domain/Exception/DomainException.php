<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Exception;

use DomainException as CoreDomainException;

/**
 * Class DomainException
 *
 * @package Bro\WorldCoreBundle\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@gmail.com>
 */
class DomainException extends CoreDomainException
{
    public const int CODE_UNAUTHORIZED = 401;
    public const int CODE_FORBIDDEN = 403;
    public const int CODE_NOT_FOUND = 404;
    public const int CODE_CONFLICT = 409;
    public const int CODE_UNPROCESSABLE_ENTITY = 422;
}
