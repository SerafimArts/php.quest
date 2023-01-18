<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\Persistence\Repository\Documentation\PageRepositoryInterface;
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
        private PageRepositoryInterface $docs,
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

    #[Route(path: '/{url}', name: 'show', methods: 'GET', priority: -1)]
    public function show(string $url): Response
    {
        $page = $this->docs->findByUrl($url);

        if ($page === null) {
            throw new NotFoundHttpException('Not Found');
        }

        $view = $this->twig->render('page/docs/show.html.twig', [
            'page' => $page,
        ]);

        return new Response($view);
    }
}
