<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Application\Utils\Interfaces;

use Bro\WorldCoreBundle\Domain\Service\Interfaces\MailerServiceInterface;
use Throwable;
use Twig\Environment as Twig;

/**
 * @package Bro\WorldCoreBundle
 */
interface MailSenderInterface
{
    public function setMailerService(
        MailerServiceInterface $mailerService,
        string $appSenderEmail,
        string $appErrorReceiverEmail,
        int $appEmailNotificationAboutError
    ): void;

    public function setTwig(Twig $twig): void;

    public function sendErrorToMail(Throwable $exception): void;
}
