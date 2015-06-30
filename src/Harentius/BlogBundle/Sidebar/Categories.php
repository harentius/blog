<?php

namespace Harentius\BlogBundle\Sidebar;

class Categories 
{
    use EntityManagerAwareTrait;

    /**
     * @param array $options
     * @return array
     */
    public function getList(array $options = [])
    {
        $categories = $this->em->getRepository('HarentiusBlogBundle:Category');

        return $categories->notEmptyChildrenHierarchy($options);
    }
}
