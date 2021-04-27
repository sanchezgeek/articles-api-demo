<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article;
use App\Service\Dto\Article as ArticleDto;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Criteria;
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
    public function list(): array
    {
        return array_map(
            fn ($article) => new ArticleDto($article->getId(), $article->getTitle()),
            $this->articleRepository->findBy([], ['title' => Criteria::ASC])
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

        return new ArticleDto($article->getId(), $article->getTitle());
    }

    /**
     * Создание статьи с указанным названием
     *
     * @param string $title
     * @return ArticleDto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(string $title): ArticleDto
    {
        $article = new Article();
        $article->setTitle($title);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return new ArticleDto($article->getId(), $article->getTitle());
    }

    /**
     * Редактирование статьи
     *
     * @param int $articleId
     * @param string $title
     *
     * @return ArticleDto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws EntityNotFoundException
     */
    public function update(int $articleId, string $title): ArticleDto
    {
        if (!$article = $this->articleRepository->find($articleId)) {
            throw new EntityNotFoundException("Article with id {$articleId} not found");
        }

        $article->setTitle($title);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return new ArticleDto($article->getId(), $article->getTitle());
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
}
