<?php

namespace AppBundle\Entity\Base;

use AppBundle\Entity\AdminUser;
use AppBundle\Entity\Category;
use AppBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Doctrine\ORM\Mapping as ORM;

abstract class Article
{
    use IdentifiableEntityTrait;
    use TimestampableEntityTrait;
    use SeoContentTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     * @SymfonyConstraints\Type(type="string")
     * @SymfonyConstraints\Length(max=1000)
     * @SymfonyConstraints\NotBlank()
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     * @Gedmo\Slug(fields={"title"}, unique=true)
     * @SymfonyConstraints\Type(type="string")
     * @SymfonyConstraints\Length(max=1000)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @SymfonyConstraints\Type(type="string")
     * @SymfonyConstraints\NotBlank()
     */
    protected $text;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(
     *      targetEntity="AppBundle\Entity\Category",
     *      inversedBy="articles"
     * )
     * @SymfonyConstraints\NotNull()
     */
    protected $category;

    /**
     * @var Tag[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="AppBundle\Entity\Tag",
     *      inversedBy="articles"
     * )
     */
    protected $tags;

    /**
     * @var AdminUser
     *
     * @ORM\ManyToOne(
     *      targetEntity="AppBundle\Entity\AdminUser"
     * )
     * @SymfonyConstraints\NotNull()
     */
    protected $author;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @SymfonyConstraints\Type(type="integer")
     * @SymfonyConstraints\Range(min=0)
     * @SymfonyConstraints\NotNull()
     */
    protected $viewsCount;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean"))
     */
    protected $isPublished;

    /**
     *
     */
    public function __construct()
    {
        $this->isPublished = false;
        $this->tags = new ArrayCollection();
        $this->viewsCount = 0;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTitle($value)
    {
        $this->title = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setText($value)
    {
        $this->text = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSlug($value)
    {
        $this->slug = $value;

        return $this;
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
     * @return AdminUser
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param AdminUser $value
     * @return $this
     */
    public function setAuthor($value)
    {
        $this->author = $value;

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
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param boolean $value
     * @return $this
     */
    public function setIsPublished($value)
    {
        $this->isPublished = $value;

        return $this;
    }
}
