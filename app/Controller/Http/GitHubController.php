<?php

declare(strict_types=1);

namespace App\Controller\Http;

use App\Infrastructure\Sync\Pipeline;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GitHubController
{
    public function __construct(
        private readonly Pipeline $pipeline,
    ) {
    }

    #[Route(path: 'update', methods: 'GET')]
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
                $result[$file] = $status->name;
            }
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => true,
                'status' => $e->getFile() . ':' . $e->getLine(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'error' => false,
            'status' => $result,
        ]);
    }
}
