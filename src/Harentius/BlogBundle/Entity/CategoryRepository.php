<?php

namespace Harentius\BlogBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    public function notEmptyChildrenHierarchy(array $options = [])
    {
        $query = $this->createQueryBuilder('c')
            ->select('c.slug, c.name, c.level, COUNT(a) AS articles_number')
            ->leftJoin('c.articles', 'a')
            ->groupBy('c.slug')
            ->getQuery()
        ;

        return $this->buildTree($query->getArrayResult(), $options);
    }
}
