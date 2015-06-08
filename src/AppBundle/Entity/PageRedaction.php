<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArticleRedactionRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({
 *      0 = "AppBundle\Entity\ArticleRedaction",
 *      1 = "AppBundle\Entity\PageRedaction",
 * })
 */
class PageRedaction extends Page
{
    /**
     * @var Page
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Page")
     */
    private $page;

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $value
     * @return $this
     */
    public function setPage($value)
    {
        $this->page = $value;

        return $this;
    }
}
