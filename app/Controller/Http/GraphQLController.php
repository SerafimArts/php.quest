<?php

declare(strict_types=1);

namespace App\Controller\Http;

use GraphQL\Error\DebugFlag;
use GraphQL\Error\Error;
use GraphQL\GraphQL;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TheCodingMachine\GraphQLite\Context\Context;
use TheCodingMachine\GraphQLite\Mappers\CannotMapTypeExceptionInterface;
use TheCodingMachine\GraphQLite\SchemaFactory;
use Twig\Environment;

final class GraphQLController
{
    /**
     * @psalm-taint-sink file $schema
     *
     * @param Environment $views
     * @param SchemaFactory $schema
     */
    public function __construct(
        private readonly bool $debug,
        private readonly Environment $views,
        private readonly SchemaFactory $schema,
        private readonly UrlGeneratorInterface $url,
    ) {
    }

    #[Route(path: '/graphql', name: 'graphql', methods: ['POST', 'GET'], stateless: true)]
    public function execute(Request $request): Response
    {
        [
            'operationName' => $operationName,
            'variables' => $variables,
            'query' => $query,
        ] = $this->parse($request);

        try {
            $result = GraphQL::executeQuery(
                $this->schema->createSchema(),
                $query,
                null,
                new Context(),
                $variables,
                $operationName,
            );
        } catch (CannotMapTypeExceptionInterface $e) {
            return new JsonResponse(new Error($e->getMessage()), 400);
        }

        return new JsonResponse($result->toArray(
            $this->getDebugFlags(),
        ));
    }

    /**
     * @return int
     */
    private function getDebugFlags(): int
    {
        $result = DebugFlag::NONE;

        if ($this->debug) {
            $result |= DebugFlag::INCLUDE_DEBUG_MESSAGE;
            $result |= DebugFlag::INCLUDE_TRACE;
        }

        return $result;
    }

    /**
     * @param Request $request
     *
     * @return array{operationName:string,variables:array,query:string}
     */
    private function parse(Request $request): array
    {
        $data = $request->getContent() === '' ? [] : $request->toArray();

        return $data + [
            'operationName' => $request->query->get('operation', ''),
            'variables' => $request->query->all('variables'),
            'query' => $request->query->get('query', '{}'),
        ];
    }

    #[Route(path: '/playground', methods: 'GET', stateless: false)]
    public function show(): Response
    {
        $view = $this->views->render('page/playground.html.twig', [
            'config' => [
                'endpoint' => $this->url->generate('graphql'),
                'settings' => [
                    'schema.polling.enable' => false,
                    'schema.polling.interval' => 200_000,
                ]
            ],
        ]);

        return new Response($view);
    }
}
