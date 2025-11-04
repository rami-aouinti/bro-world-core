<?php

namespace Bro\WorldCoreBundle\Service;

use DateTimeImmutable;

final class Clock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}