<?php

declare(strict_types=1);

namespace App\Entity;

use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\ArticleRepository as BaseArticleRepository;

class ArticleRepository extends BaseArticleRepository
{
    public function findStatistics(): array
    {
        $q = $this->createQueryBuilder('a');
        $q1 = $this->createQueryBuilder('ap');

        return $q
            ->select('COUNT(a.id) AS countPublished,
                SUM(a.viewsCount) AS viewsCount,
                SUM(a.likesCount) AS likesCount,
                SUM(a.disLikesCount) as disLikesCount
            ')
            ->addSelect('(' .
                $q1
                    ->select('MIN(ap.publishedAt)')
                    ->where('ap.published = :isPublished')
                    ->setParameter(':isPublished', true)
                    ->orderBy('ap.publishedAt', 'ASC')
                    ->setMaxResults(1)
                    ->getDQL()
                . ')AS firstArticlePublicationDate'
            )
            ->where('a.published = :isPublished')
            ->setParameter(':isPublished', true)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findMostPopular(): Article
    {
        return $this->createQueryBuilder('a')
            ->where('a.published = :isPublished')
            ->setParameter(':isPublished', true)
            ->orderBy('a.viewsCount', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
