<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Infrastructure\Service;

use Bro\WorldCoreBundle\Domain\Message\Interfaces\MessageHighInterface;
use Bro\WorldCoreBundle\Domain\Message\Interfaces\MessageLowInterface;
use Bro\WorldCoreBundle\Domain\Service\Interfaces\MessageServiceInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @package Bro\WorldCoreBundle
 */
class MessageService implements MessageServiceInterface
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @throws ExceptionInterface
     */
    public function sendMessage(MessageHighInterface|MessageLowInterface $message): self
    {
        $this->bus->dispatch(new Envelope($message));

        return $this;
    }
}
