<?php

namespace Harentius\WidgetsBundle\Entity;

use Harentius\BlogBundle\Entity\Base\IdentifiableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;

/**
 * @ORM\Entity(repositoryClass="Harentius\WidgetsBundle\Entity\WidgetRepository")
 */
class Widget
{
    use IdentifiableEntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @SymfonyConstraints\Type(type="string")
     * @SymfonyConstraints\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @SymfonyConstraints\Type(type="string")
     * @SymfonyConstraints\NotBlank()
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @SymfonyConstraints\Type(type="string")
     */
    private $route;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     * @SymfonyConstraints\Type(type="array")
     */
    private $routeParameters;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @SymfonyConstraints\Type(type="string")
     * @SymfonyConstraints\NotBlank()
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @SymfonyConstraints\Type(type="string")
     */
    private $backLink;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @SymfonyConstraints\Type(type="boolean")
     * @SymfonyConstraints\NotNull()
     */
    private $isPhpUse;

    /**
     * @var array
     */
    private $routeData;

    /**
     *
     */
    public function __construct()
    {
        $this->isPhpUse = false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setContent($value)
    {
        $this->content = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRoute($value)
    {
        $this->route = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setRouteParameters($value)
    {
        $this->routeParameters = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPosition($value)
    {
        $this->position = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getBackLink()
    {
        return $this->backLink;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBackLink($value)
    {
        $this->backLink = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsPhpUse()
    {
        return $this->isPhpUse;
    }

    /**
     * @param boolean $value
     * @return $this
     */
    public function setIsPhpUse($value)
    {
        $this->isPhpUse = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteData()
    {
        return $this->routeData;
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setRouteData($value)
    {
        $this->routeData = $value;

        return $this;
    }
}
