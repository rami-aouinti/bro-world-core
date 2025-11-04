<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Rest\Traits\Methods;

use Bro\WorldCoreBundle\Application\Rest\Interfaces\RestDeleteResourceInterface;
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
trait DeleteMethod
{
    /**
     * Generic 'deleteMethod' method for REST resources.
     *
     * @param array<int, string>|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    public function deleteMethod(Request $request, string $id, ?array $allowedHttpMethods = null): Response
    {
        /** @var RestResourceInterface|RestDeleteResourceInterface $resource */
        $resource = $this->getResourceForMethod($request, $allowedHttpMethods ?? [Request::METHOD_DELETE]);

        try {
            $entityManagerName = RequestHandler::getTenant($request);

            // Fetch data from database
            return $this
                ->getResponseHandler()
                ->createResponse(
                    $request,
                    $resource->delete(id: $id, entityManagerName: $entityManagerName), /** @phpstan-ignore-next-line */
                    $resource
                );
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id, $entityManagerName ?? null);
        }
    }
}
