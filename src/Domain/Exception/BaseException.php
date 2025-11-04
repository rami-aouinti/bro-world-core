<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Exception;

use Bro\WorldCoreBundle\Domain\Exception\Interfaces\ExceptionInterface;
use Exception;

/**
 * @package Bro\WorldCoreBundle
 */
abstract class BaseException extends Exception implements ExceptionInterface
{
}
