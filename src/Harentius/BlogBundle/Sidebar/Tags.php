<?php

namespace Harentius\BlogBundle\Sidebar;

class Tags
{
    use EntityManagerAwareTrait;

    /**
     * @var int
     */
    private $sidebarTagsLimit;

    /**
     * @var array
     */
    private $sidebarTagSizes;

    /**
     * @param int $sidebarTagsLimit
     * @param array $sidebarTagsSize
     */
    public function __construct($sidebarTagsLimit, $sidebarTagsSize)
    {
        $this->sidebarTagsLimit = $sidebarTagsLimit;
        $this->sidebarTagSizes = $sidebarTagsSize;
    }

    /**
     * @return array
     */
    public function getList()
    {
        $tags = $this->em->getRepository('HarentiusBlogBundle:Tag')
            ->findMostPopularLimited($this->sidebarTagsLimit)
        ;

        if (!$tags) {
            return $tags;
        }

        $maxWeight = $tags[0]['weight'];

        foreach ($tags as $key => $tag) {
            $percentage = 100;
            $minDiff = 1;

            foreach ($this->sidebarTagSizes as $tagPercent) {
                if (($diff = abs($tag['weight']/$maxWeight - $tagPercent/100)) < $minDiff) {
                    $minDiff = $diff;
                    $percentage = $tagPercent;
                }
            }

            $tags[$key]['percentage'] = $percentage;
        }

        return $tags;
    }
}
