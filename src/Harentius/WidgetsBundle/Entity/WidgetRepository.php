<?php

namespace Harentius\WidgetsBundle\Entity;

use Doctrine\ORM\EntityRepository;

class WidgetRepository extends EntityRepository
{
    /**
     * @param array $route
     * @param $position
     * @return Widget[]
     */
    public function findByRouteOrNullRouteAndPositionOrderedByPriority(array $route, $position)
    {
        $qb = $this->createQueryBuilder('w');

        return $qb
            ->where($qb->expr()->in('w.route', [
                serialize($route),
                serialize(['name' => null, 'parameters' => []])
            ]))
            ->andWhere('w.position = :position')
            ->setParameter(':position', $position)
            ->orderBy('w.priority', 'DESC')
            ->getQuery()
            ->execute()
        ;
    }
}
