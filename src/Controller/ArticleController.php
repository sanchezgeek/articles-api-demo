<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\ArticlesService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends ApiController
{
    private ArticleRepository $articleRepository;
    private ArticlesService $articlesService;

    public function __construct(
        ArticleRepository $articleRepository,
        ArticlesService $articlesService
    ) {
        $this->articleRepository = $articleRepository;
        $this->articlesService = $articlesService;
    }

    public function listAction(Request $request): Response
    {
        return $this->response(
            $this->articlesService->list()
        );
    }

    public function viewAction(int $articleId): Response
    {
        try {
            return $this->response(
                $this->articlesService->get($articleId)
            );
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(ArticleType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        return $this->response(
            $this->articlesService->create($form->getData()->getTitle())
        );
    }

    public function updateAction(int $articleId, Request $request): Response
    {
        if (!$article = $this->articleRepository->find($articleId)) {
            throw new NotFoundHttpException('Article not found');
        }

        $form = $this->buildForm(ArticleType::class, $article, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        return $this->response(
            $this->articlesService->update($articleId, $form->getData()->getTitle())
        );
    }

    public function deleteAction(int $articleId): Response
    {
        try {
            return $this->response([
                'success' => $this->articlesService->delete($articleId)
            ]);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
}
