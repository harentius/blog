<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function findPublishedOrdered()
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPublished = :isPublished')
            ->setParameter(':isPublished', true)
            ->orderBy('p.order', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }
}
