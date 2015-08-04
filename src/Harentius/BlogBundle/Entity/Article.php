<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Harentius\BlogBundle\Entity\Base\AbstractPost;
use Harentius\BlogBundle\Entity\Base\ArticleChangeableFieldsEntityTrait;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Harentius\BlogBundle\Entity\ArticleRepository")
 */
class Article extends AbstractPost
{
    use ArticleChangeableFieldsEntityTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @SymfonyConstraints\Type(type="integer")
     * @SymfonyConstraints\Range(min=0)
     * @SymfonyConstraints\NotNull()
     */
    private $viewsCount;

    private $likesCount;

    private $disLikesCount;

    /**
     *
     */
    public function __construct()
    {
        $this->isPublished = false;
        $this->viewsCount = 0;
        $this->tags = new ArrayCollection();
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
