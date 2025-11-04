<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Rest\Traits\Actions\Anon;

use Bro\WorldCoreBundle\Application\DTO\Interfaces\RestDtoInterface;
use Bro\WorldCoreBundle\Transport\Rest\Traits\Methods\UpdateMethod;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Throwable;

/**
 * Trait to add 'updateAction' for REST controllers for anonymous users.
 *
 * @see \Bro\WorldCoreBundle\Transport\Rest\Traits\Methods\UpdateMethod for detailed documents.
 *
 * @package Bro\WorldCoreBundle
 */
trait UpdateAction
{
    use UpdateMethod;

    /**
     * Update entity with new data, accessible for anonymous users.
     *
     * @throws Throwable
     */
    #[Route(
        path: '/{id}',
        requirements: [
            'id' => Requirement::UUID_V1,
        ],
        methods: [Request::METHOD_PUT],
    )]
    #[OA\RequestBody(
        request: 'body',
        description: 'object',
        content: new JsonContent(
            type: 'object',
            example: [
                'param' => 'value',
            ],
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new JsonContent(
            type: 'object',
            example: [],
        ),
    )]
    public function updateAction(Request $request, RestDtoInterface $restDto, string $id): Response
    {
        return $this->updateMethod($request, $restDto, $id);
    }
}
