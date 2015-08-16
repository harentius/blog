<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ArticleRepository extends EntityRepository
{
    /**
     * @param Category $category
     * @return Query
     */
    public function findPublishedByCategoryQuery(Category $category)
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->where('a.category IN
                (SELECT c FROM HarentiusBlogBundle:Category c
                 WHERE c.left >= :left AND c.right <= :right AND c.root = :root)'
            )
            ->andWhere('a.isPublished = :isPublished')
            ->setParameters([
                ':left' => $category->getLeft(),
                ':right' => $category->getRight(),
                ':root' => $category->getRoot(),
                ':isPublished' => true,
            ])
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
        ;
    }

    /**
     * @param Tag $tag
     * @return Query
     */
    public function findByTagQuery(Tag $tag)
    {
        return $this->createQueryBuilder('a')
            ->join('a.tags', 't')
            ->where('t = :tag')
            ->setParameter(':tag', $tag)
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
        ;
    }

    /**
     * @param string $year
     * @param string $month
     * @return Query
     */
    public function findPublishedByYearMonthQuery($year, $month = null)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->where('YEAR(a.publishedAt) = :year')
            ->andWhere('a.isPublished = :isPublished')
            ->setParameters([
                ':year' => $year,
                ':isPublished' => true,
            ])
            ->orderBy('a.publishedAt', 'DESC')
        ;

        if ($month) {
            $qb
                ->andWhere('MONTH(a.publishedAt) = :month')
                ->setParameter(':month', $month)
            ;
        }

        return $qb->getQuery();
    }

    /**
     * @param null $categorySlug
     * @return Query
     */
    public function findPublishedByCategorySlugLimitedQuery($categorySlug = null)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->where('a.isPublished = :isPublished')
            ->setParameter(':isPublished', true)
            ->orderBy('a.publishedAt', 'DESC')
        ;

        if ($categorySlug !== null) {
            $qb
                ->join('a.category', 'c')
                ->where('c.slug = :slug')
                ->setParameter(':slug', $categorySlug)
            ;
        }

        return $qb->getQuery();
    }
}
