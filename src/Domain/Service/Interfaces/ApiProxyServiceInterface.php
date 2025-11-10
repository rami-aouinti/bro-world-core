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
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     *
     * @throws Throwable
     */
    public function request(
        string $method,
        string $type,
        Request $request,
        array $body = [],
        string $path = '',
        array $options = [],
    ): array;

    /**
     * @param array<string, mixed> $body
     * @param array<string, mixed> $uploadOptions
     *
     * @throws Throwable
     */
    public function requestFile(
        string $method,
        string $type,
        Request $request,
        array $body = [],
        string $path = '',
        array $uploadOptions = [],
    ): array;
}
