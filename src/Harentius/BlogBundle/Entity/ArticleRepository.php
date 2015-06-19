<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * @param string $categorySlug
     * @return mixed
     */
    public function findOneByCategorySlug($categorySlug)
    {
        return $this->createQueryBuilder('a')
            ->join('a.category', 'c')
            ->where('c.slug = :categorySlug')
            ->setParameter(':categorySlug', $categorySlug)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @param string $tagSlug
     * @return mixed
     */
    public function findByTagSlug($tagSlug)
    {
        return $this->createQueryBuilder('a')
            ->join('a.tags', 't')
            ->where('t.slug = :tagSlug')
            ->setParameter(':tagSlug', $tagSlug)
            ->getQuery()
            ->execute()
        ;
    }
}
