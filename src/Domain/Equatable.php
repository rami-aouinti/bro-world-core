<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain;

/**
 * interface Equatable
 */
interface Equatable
{
    public function equals(Equatable $other): bool;
}
