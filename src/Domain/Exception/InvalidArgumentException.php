<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Exception;

/**
 * Class InvalidArgumentException
 *
 * @package Bro\WorldCoreBundle\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@gmail.com>
 */
final class InvalidArgumentException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message, self::CODE_UNPROCESSABLE_ENTITY);
    }
}
