<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    /**
     * @param Category $category
     * @return mixed
     */
    public function findByCategory(Category $category)
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->join('a.category', 'ca')
            ->where('a.category IN
                (SELECT c FROM HarentiusBlogBundle:Category c
                 WHERE c.left >= :left AND c.right <= :right AND c.root = :root)'
            )
            ->setParameters([
                ':left' => $category->getLeft(),
                ':right' => $category->getRight(),
                ':root' => $category->getRoot(),
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
