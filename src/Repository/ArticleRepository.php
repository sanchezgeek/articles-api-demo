<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findByTags(array $tags = [])
    {
        $tagsCount = count($tags);
        $placeholders = implode(',', array_fill(0, $tagsCount, '?'));

        $idsQuery = "
            SELECT a.id FROM article a
                INNER JOIN article_tag at ON at.article_id = a.id
                INNER JOIN tag t ON at.tag_id = t.id
            where t.name IN ($placeholders)
            group by a.id
            having count(t.id) = {$tagsCount}
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($idsQuery);
        $stmt->executeStatement($tags);
        $ids = $stmt->fetchAll();

        return $this->createQueryBuilder('a')
            ->andWhere('a.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}
