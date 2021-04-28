<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article;
use App\Entity\Tag;
use App\Service\Dto\Article as ArticleDto;
use App\Service\Dto\Tag as TagDto;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
     * Get articles list
     *
     * @return ArticleDto[]
     */
    public function list(array $tags = []): array
    {
        return array_map(
            fn (Article $article) => $this->buildArticleDto($article),
            $this->articleRepository->findByTags($tags)
        );
    }

    /**
     * Get article by specified id
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
     * Article create
     *
     * @param string $title
     * @param array|Tag[] $tags
     * @return ArticleDto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(string $title, array $tags = []): ArticleDto
    {
        $tags = array_filter($tags, fn($tag) => $tag instanceof Tag);

        $article = new Article();
        $article->setTitle($title);
        $article->setTags(new ArrayCollection($tags));

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->buildArticleDto($article);
    }

    /**
     * Article edit
     *
     * @param int $articleId
     * @param string $title
     * @param array|Tag[] $tags
     *
     * @return ArticleDto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(int $articleId, string $title, array $tags = []): ArticleDto
    {
        if (!$article = $this->articleRepository->find($articleId)) {
            throw new EntityNotFoundException('Article not found');
        }

        $tags = array_filter($tags, fn($tag) => $tag instanceof Tag);

        $article->setTitle($title);
        $article->setTags(new ArrayCollection($tags));

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->buildArticleDto($article);
    }

    /**
     * Article delete
     *
     * @param int $articleId
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
