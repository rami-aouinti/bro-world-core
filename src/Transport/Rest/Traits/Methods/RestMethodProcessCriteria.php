<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Rest\Traits\Methods;

use Symfony\Component\HttpFoundation\Request;

/**
 * @package Bro\WorldCoreBundle
 */
trait RestMethodProcessCriteria
{
    /**
     * {@inheritdoc}
     *
     * @param array<int|string, string|array<mixed>> $criteria
     */
    public function processCriteria(array &$criteria, Request $request, string $method): void
    {
    }
}
