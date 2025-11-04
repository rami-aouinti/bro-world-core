<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\Rest\Interfaces\RestListResourceInterface;
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
trait FindMethod
{
    /**
     * Generic 'findMethod' method for REST resources.
     *
     * @param array<int, string>|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    public function findMethod(Request $request, ?array $allowedHttpMethods = null): Response
    {
        /** @var RestResourceInterface|RestListResourceInterface $resource */
        $resource = $this->getResourceForMethod($request, $allowedHttpMethods ?? [Request::METHOD_GET]);
        // Determine used parameters
        $orderBy = RequestHandler::getOrderBy($request);
        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);
        $search = RequestHandler::getSearchTerms($request);

        try {
            $criteria = RequestHandler::getCriteria($request);
            $entityManagerName = RequestHandler::getTenant($request);
            $this->processCriteria($criteria, $request, __METHOD__);

            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $resource->find(
                        $criteria,
                        $orderBy,
                        $limit,
                        $offset,
                        $search,
                        $entityManagerName
                    ), /** @phpstan-ignore-next-line */
                    $resource
                );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException(
                exception: $exception,
                entityManagerName: $entityManagerName ?? null
            );
        }
    }
}
