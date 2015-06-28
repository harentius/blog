<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * @param Category $category
     * @return mixed
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
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @param Tag $tag
     * @return mixed
     */
    public function findByTag(Tag $tag)
    {
        return $this->createQueryBuilder('a')
            ->join('a.tags', 't')
            ->where('t = :tag')
            ->setParameter(':tag', $tag)
            ->getQuery()
            ->execute()
        ;
    }
}
