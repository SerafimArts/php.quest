<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\Sync\Pipeline;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SystemController
{
    public function __construct(
        private readonly Pipeline $pipeline,
    ) {
    }

    #[Route(path: 'update', methods: 'GET', priority: 1)]
    public function ping(): Response
    {
        return new JsonResponse(['response' => 'PING']);
    }

    #[Route(path: 'update', methods: 'POST')]
    public function update(): Response
    {
        $result = [];

        try {
            foreach ($this->pipeline->process() as $status => $file) {
                $result[$file] = $status;
            }
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => true,
                'status' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'error' => false,
            'status' => $result,
        ]);
    }
}
