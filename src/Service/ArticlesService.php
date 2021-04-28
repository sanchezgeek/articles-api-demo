<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article;
use App\Entity\Tag;
use App\Service\Dto\Article as ArticleDto;
use App\Service\Dto\Tag as TagDto;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

class ArticlesService
{
    private EntityManager $entityManager;
    private ArticleRepository $articleRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository
    ) {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    /**
     * Получение списка статей
     *
     * @return Article[]
     */
    public function list(array $tags = []): array
    {
        return array_map(
            fn (Article $article) => $this->buildArticleDto($article),
            $this->articleRepository->findByTags($tags)
        );
    }

    /**
     * Получение одной статьи
     *
     * @param int $articleId
     * @return ArticleDto
     *
     * @throws ORMException
     * @throws EntityNotFoundException
     */
    public function get(int $articleId): ArticleDto
    {
        if (!$article = $this->articleRepository->find($articleId)) {
            throw new EntityNotFoundException("Article with id {$articleId} not found");
        }

        return $this->buildArticleDto($article);
    }

    /**
     * Сохранение статьи
     *
     * @param Article $article
     * @return ArticleDto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Article $article): ArticleDto
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->buildArticleDto($article);
    }

    /**
     * Удаление статьи
     *
     * @param int $articleId
     *
     * @return bool
     *
     * @throws ORMException
     * @throws EntityNotFoundException
     */
    public function delete(int $articleId): bool
    {
        if (!$article = $this->articleRepository->find($articleId)) {
            throw new EntityNotFoundException("Article with id {$articleId} not found");
        }

        $this->entityManager->remove($article);
        $this->entityManager->flush();

        return true;
    }

    private function buildArticleDto(Article $article): ArticleDto
    {
        $tags =  array_map(
            fn (Tag $tag) => new TagDto($tag->getId(), $tag->getName()),
            $article->getTags()->toArray()
        );

        return new ArticleDto($article->getId(), $article->getTitle(), $tags);
    }
}
