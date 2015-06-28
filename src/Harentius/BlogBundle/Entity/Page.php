<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Harentius\BlogBundle\Entity\Base\Article as BaseArticle;

/**
 * @ORM\Entity(repositoryClass="Harentius\BlogBundle\Entity\PageRepository")
 */
class Page extends BaseArticle
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @SymfonyConstraints\NotNull()
     * @SymfonyConstraints\Type(type="bool")
     */
    private $showInMainMenu;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, name="_order")
     * @SymfonyConstraints\Type(type="integer")
     */
    private $order;

    /**
     *
     */
    public function __construct()
    {
        $this->showInMainMenu = false;
    }

    /**
     * @return bool
     */
    public function getShowInMainMenu()
    {
        return $this->showInMainMenu;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setShowInMainMenu($value)
    {
        $this->showInMainMenu = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setOrder($value)
    {
        $this->order = $value;

        return $this;
    }
}
