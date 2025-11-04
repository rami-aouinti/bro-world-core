<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Rest\Traits\Actions\Anon;

use Bro\WorldCoreBundle\Transport\Rest\Traits\Methods\CountMethod;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

/**
 * Trait to add 'countAction' for REST controllers for anonymous users.
 *
 * @see \Bro\WorldCoreBundle\Transport\Rest\Traits\Methods\CountMethod for detailed documents.
 *
 * @package Bro\WorldCoreBundle
 */
trait CountAction
{
    use CountMethod;

    /**
     * Count entities, accessible for anonymous users.
     *
     * @throws Throwable
     */
    #[Route(
        path: '/count',
        methods: [Request::METHOD_GET],
    )]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new JsonContent(
            properties: [
                new Property(property: 'count', type: 'integer'),
            ],
            type: 'object',
            example: [
                'count' => 1,
            ],
        ),
    )]
    public function countAction(Request $request): Response
    {
        return $this->countMethod($request);
    }
}
