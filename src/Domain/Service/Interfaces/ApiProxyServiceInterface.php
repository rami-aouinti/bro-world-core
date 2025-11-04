<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Domain\Service\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @package Bro\WorldCoreBundle
 */
interface ApiProxyServiceInterface
{
    /**
     * @throws Throwable
     */
    public function request(string $method, string $type, Request $request, array $body = [], string $path = ''): array;

    /**
     * @throws Throwable
     */
    public function requestFile(string $method, string $type, Request $request, array $body = [], string $path = ''): array;
}
