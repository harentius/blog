<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function findPublishedNotIndexOrdered($slug)
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPublished = :isPublished')
            ->andWhere('p.slug <> :slug')
            ->setParameters([
                ':isPublished' => true,
                ':slug' => $slug,
            ])
            ->orderBy('p.order', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }
}
