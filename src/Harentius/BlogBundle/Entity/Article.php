<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Eko\FeedBundle\Item\Writer\ItemInterface;
use Harentius\BlogBundle\Entity\Base\AbstractPost;
use Harentius\BlogBundle\Entity\Base\ArticleChangeableFieldsEntityTrait;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Harentius\BlogBundle\Entity\ArticleRepository")
 */
class Article extends AbstractPost implements ItemInterface
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

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @SymfonyConstraints\Type(type="integer")
     * @SymfonyConstraints\Range(min=0)
     * @SymfonyConstraints\NotNull()
     */
    private $likesCount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @SymfonyConstraints\Type(type="integer")
     * @SymfonyConstraints\Range(min=0)
     * @SymfonyConstraints\NotNull()
     */
    private $disLikesCount;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     * @SymfonyConstraints\Type(type="array")
     * @SymfonyConstraints\NotNull()
     */
    private $attributes;

    /**
     *
     */
    public function __construct()
    {
        $this->isPublished = false;
        $this->viewsCount = 0;
        $this->likesCount = 0;
        $this->disLikesCount = 0;
        $this->tags = new ArrayCollection();
        $this->attributes = [];
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

    /**
     * @return int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setLikesCount($value)
    {
        $this->likesCount = $value;

        return $this;
    }

    /**
     *
     */
    public function increaseLikesCount()
    {
        $this->likesCount = $this->getLikesCount() + 1;
    }

    /**
     * @return int
     */
    public function getDisLikesCount()
    {
        return $this->disLikesCount;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setDisLikesCount($value)
    {
        $this->disLikesCount = $value;

        return $this;
    }

    /**
     *
     */
    public function increaseDisLikesCount()
    {
        $this->disLikesCount = $this->getDisLikesCount() + 1;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setAttributes($value)
    {
        $this->attributes = $value;

        return $this;
    }

    // RSS
    /**
     * @inheritdoc
     */
    public function getFeedItemTitle()
    {
        return $this->getTitle();
    }

    /**
     * @inheritdoc
     */
    public function getFeedItemDescription()
    {
        return $this->getMetaDescription();
    }

    /**
     * @inheritdoc
     */
    public function getFeedItemLink()
    {
        return $this->getSlug();
    }

    /**
     * @inheritdoc
     */
    public function getFeedItemPubDate()
    {
        return $this->getPublishedAt();
    }
}
