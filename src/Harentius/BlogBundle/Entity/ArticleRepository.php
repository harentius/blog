<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * @param Category $category
     * @return Article[]
     */
    public function findPublishedByCategory(Category $category)
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
            ->execute()
        ;
    }

    /**
     * @param Tag $tag
     * @return Article[]
     */
    public function findByTag(Tag $tag)
    {
        return $this->createQueryBuilder('a')
            ->join('a.tags', 't')
            ->where('t = :tag')
            ->setParameter(':tag', $tag)
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @param string $year
     * @param string $month
     * @return Article[]
     */
    public function findPublishedByYearMonth($year, $month = null)
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

        return $qb->getQuery()->execute();
    }

    /**
     * @param $limit
     * @param null $categorySlug
     * @return mixed
     */
    public function findByCategorySlugLimited($limit, $categorySlug = null)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
        ;

        if ($categorySlug !== null) {
            $qb
                ->join('a.category', 'c')
                ->where('c.slug = :slug')
                ->setParameter(':slug', $categorySlug)
            ;
        }

        return $qb->getQuery()->execute();
    }
}
