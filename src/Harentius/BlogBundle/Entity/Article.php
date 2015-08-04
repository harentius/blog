<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Harentius\BlogBundle\Entity\Base\Article as BaseArticle;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Harentius\BlogBundle\Entity\ArticleRepository")
 */
class Article extends BaseArticle
{
    /**
     * @var Category
     *
     * @ORM\ManyToOne(
     *      targetEntity="Harentius\BlogBundle\Entity\Category",
     *      inversedBy="articles"
     * )
     * @SymfonyConstraints\NotNull()
     */
    private $category;

    /**
     * @var Tag[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Harentius\BlogBundle\Entity\Tag",
     *      inversedBy="articles"
     * )
     */
    private $tags;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @SymfonyConstraints\Type(type="integer")
     * @SymfonyConstraints\Range(min=0)
     * @SymfonyConstraints\NotNull()
     */
    private $viewsCount;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->tags = new ArrayCollection();
        $this->viewsCount = 0;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $value
     * @return $this
     */
    public function setCategory($value)
    {
        $this->category = $value;

        return $this;
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag[] $value
     * @return $this
     */
    public function setTags($value)
    {
        $this->tags = $value;

        return $this;
    }

    /**
     * @param Tag $value
     * @return $this
     */
    public function addTag($value)
    {
        $this->tags[] = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setViewsCount($value)
    {
        $this->viewsCount = $value;

        return $this;
    }

    /**
     *
     */
    public function increaseViewsCount()
    {
        $this->viewsCount = $this->getViewsCount() + 1;
    }
}
