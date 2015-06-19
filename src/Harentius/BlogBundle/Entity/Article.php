<?php

namespace Harentius\BlogBundle\Entity;

use Harentius\BlogBundle\Entity\Base\Article as BaseArticle;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Harentius\BlogBundle\Entity\ArticleRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({
 *      0 = "Harentius\BlogBundle\Entity\Article",
 *      1 = "Harentius\BlogBundle\Entity\Page",
 * })
 */
class Article extends BaseArticle
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @SymfonyConstraints\DateTime
     */
    private $publishedAt;

    /**
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTime $value
     * @return $this
     */
    public function setPublishedAt($value)
    {
        $this->publishedAt = $value;

        return $this;
    }
}
