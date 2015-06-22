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
        $q = $this->createQueryBuilder('c')
            ->select('c.slug, c.name, c.level, COUNT(a) AS articles_number')
            ->innerJoin('c.articles', 'a')
            ->groupBy('a.category')
            ->getQuery()
        ;

        return $this->buildTree($q->getArrayResult(), $options);
    }
}
