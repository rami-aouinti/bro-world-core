<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\Rest\Interfaces\RestCountResourceInterface;
use Bro\WorldCoreBundle\Application\Rest\Interfaces\RestResourceInterface;
use Bro\WorldCoreBundle\Transport\Rest\RequestHandler;
use Bro\WorldCoreBundle\Transport\Rest\ResponseHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @package Bro\WorldCoreBundle
 *
 * @method ResponseHandler getResponseHandler()
 */
trait CountMethod
{
    /**
     * Generic 'countMethod' method for REST resources.
     *
     * @param array<int, string>|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    public function countMethod(Request $request, ?array $allowedHttpMethods = null): Response
    {
        /** @var RestResourceInterface|RestCountResourceInterface $resource */
        $resource = $this->getResourceForMethod($request, $allowedHttpMethods ?? [Request::METHOD_GET]);
        // Determine used parameters
        $search = RequestHandler::getSearchTerms($request);

        try {
            $criteria = RequestHandler::getCriteria($request);
            $entityManagerName = RequestHandler::getTenant($request);
            $this->processCriteria($criteria, $request, __METHOD__);

            return $this
                ->getResponseHandler()
                ->createResponse($request, [
                    'count' => $resource->count($criteria, $search, $entityManagerName),
                ], $resource); /** @phpstan-ignore-line */
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException(
                exception: $exception,
                entityManagerName: $entityManagerName ?? null
            );
        }
    }
}
