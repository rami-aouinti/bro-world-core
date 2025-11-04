<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Service\Interfaces;

use Throwable;

/**
 * @package Bro\WorldCoreBundle
 */
interface MailerServiceInterface
{
    /**
     * Send mail to recipients
     *
     * @throws Throwable
     */
    public function sendMail(string $title, string $from, string $to, string $body): void;
}
