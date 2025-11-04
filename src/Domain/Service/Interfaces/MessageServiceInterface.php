<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Service\Interfaces;

use Bro\WorldCoreBundle\Domain\Message\Interfaces\MessageHighInterface;
use Bro\WorldCoreBundle\Domain\Message\Interfaces\MessageLowInterface;
use Throwable;

/**
 * @package Bro\WorldCoreBundle
 */
interface MessageServiceInterface
{
    /**
     * @throws Throwable
     */
    public function sendMessage(MessageHighInterface|MessageLowInterface $message): self;
}
