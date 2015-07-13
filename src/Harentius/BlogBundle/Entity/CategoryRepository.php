<?php

namespace Harentius\BlogBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    /**
     * @param array $options
     * @return mixed
     */
    public function notEmptyChildrenHierarchy(array $options = [])
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.slug, c.name, c.level, c.root, c.left, c.right')
            ->addSelect('COUNT(a) AS articles_number')
                ->from('HarentiusBlogBundle:Article', 'a')
                ->where('a.category IN
                    (SELECT cc FROM HarentiusBlogBundle:Category cc
                     WHERE cc.left >= c.left AND cc.right <= c.right AND cc.root = c.root)'
                )
                ->andWhere('a.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->orderBy('c.root, c.left', 'ASC')
            ->groupBy('c')
            ->getQuery()
        ;

        return $this->buildTree($qb->getArrayResult(), $options);
    }
}
