<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Rest\Traits\Actions\Anon;

use Bro\WorldCoreBundle\Transport\Rest\Traits\Methods\FindMethod;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

/**
 * Trait to add 'findAction' for REST controllers for anonymous users.
 *
 * @see \Bro\WorldCoreBundle\Transport\Rest\Traits\Methods\FindMethod for detailed documents.
 *
 * @package Bro\WorldCoreBundle
 */
trait FindAction
{
    use FindMethod;

    /**
     * Get list of entities, accessible for anonymous users.
     *
     * @throws Throwable
     */
    #[Route(
        path: '',
        methods: [Request::METHOD_GET],
    )]
    #[OA\Response(
        response: 200,
        description: 'success',
        content: new JsonContent(
            type: 'array',
            items: new OA\Items(type: 'string'),
        ),
    )]
    public function findAction(Request $request): Response
    {
        return $this->findMethod($request);
    }
}
