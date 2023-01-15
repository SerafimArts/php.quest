<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Documentation;
use App\Infrastructure\Persistence\Repository\DocsRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final readonly class DocsController
{
    /**
     * @param Environment $twig
     */
    public function __construct(
        private Environment $twig,
        private DocsRepositoryInterface $docs,
    ) {
    }

    #[Route(path: '/', name: 'home', methods: 'GET')]
    public function index(): Response
    {
        $page = $this->docs->findOneBy([]);

        if ($page === null) {
            throw new NotFoundHttpException('Not Found');
        }

        return new RedirectResponse('/' . $page->getSlug());
    }

    #[Route(path: '/{urn}', name: 'show', methods: 'GET')]
    public function show(string $urn): Response
    {
        $page = $this->docs->findOneBy(['slug' => $urn]);

        if ($page === null) {
            throw new NotFoundHttpException('Not Found');
        }

        return $this->response($page);
    }

    private function response(Documentation $page): Response
    {
        $view = $this->twig->render('page/docs/show.html.twig', [
            'page' => $page,
        ]);

        return new Response($view);
    }
}
