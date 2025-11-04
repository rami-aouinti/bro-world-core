<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Rest\Traits\Actions\Anon;

use Bro\WorldCoreBundle\Application\DTO\Interfaces\RestDtoInterface;
use Bro\WorldCoreBundle\Transport\Rest\Traits\Methods\CreateMethod;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

/**
 * Trait to add 'createAction' for REST controllers for anonymous users.
 *
 * @see \Bro\WorldCoreBundle\Transport\Rest\Traits\Methods\CreateMethod for detailed documents.
 *
 * @package Bro\WorldCoreBundle
 */
trait CreateAction
{
    use CreateMethod;

    /**
     * Create entity, accessible for anonymous users.
     *
     * @throws Throwable
     */
    #[Route(
        path: '',
        methods: [Request::METHOD_POST],
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
        response: 201,
        description: 'created',
        content: new JsonContent(
            type: 'object',
            example: [],
        ),
    )]
    public function createAction(Request $request, RestDtoInterface $restDto): Response
    {
        return $this->createMethod($request, $restDto);
    }
}
