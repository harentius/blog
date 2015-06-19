<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class TagRepository extends EntityRepository
{
    /**
     * @param $limit
     * @return mixed
     */
    public function findMostPopularLimited($limit)
    {
        $tags = $this->createQueryBuilder('t')
            ->select('t.name, t.slug, COUNT(a) as weight')
            ->join('t.articles', 'a')
            ->orderBy('weight', 'DESC')
            ->groupBy('t.slug')
            ->setMaxResults($limit)
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY)
            ->execute()
        ;

        $result = [];

        foreach ($tags as $tag) {
            $result[] = $tag;
        }

        return $result;
    }
}
