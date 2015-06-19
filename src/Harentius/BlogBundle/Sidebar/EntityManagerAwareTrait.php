<?php

namespace Harentius\BlogBundle\Sidebar;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerAwareTrait
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}
