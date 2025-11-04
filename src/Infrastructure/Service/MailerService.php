<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Infrastructure\Service;

use Bro\WorldCoreBundle\Domain\Service\Interfaces\MailerServiceInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @package Bro\WorldCoreBundle
 */
class MailerService implements MailerServiceInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function sendMail(string $title, string $from, string $to, string $body): void
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($title)
            ->html($body);

        $this->mailer->send($email);
    }
}
