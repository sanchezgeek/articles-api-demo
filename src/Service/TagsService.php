<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Tag;
use App\Service\Dto\Tag as TagDto;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

class TagsService
{
    private EntityManager $entityManager;
    private TagRepository $tagRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TagRepository $tagRepository
    ) {
        $this->entityManager = $entityManager;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Создание тега
     *
     * @param string $name
     *
     * @return TagDto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(string $name): TagDto
    {
        $tag = new Tag();
        $tag->setName($name);

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return new TagDto($tag->getId(), $tag->getName());
    }

    /**
     * Редактирование тега
     *
     * @param int $tagId
     * @param string $name
     *
     * @return TagDto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws EntityNotFoundException
     */
    public function update(int $tagId, string $name): TagDto
    {
        if (!$tag = $this->tagRepository->find($tagId)) {
            throw new EntityNotFoundException("Tag `{$tagId}` not found");
        }

        $tag->setName($name);

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return new TagDto($tag->getId(), $tag->getName());
    }
}
