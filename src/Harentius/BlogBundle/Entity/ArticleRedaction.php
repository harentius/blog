<?php

namespace Harentius\BlogBundle\Entity;

use Harentius\BlogBundle\Entity\Base\Article;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Harentius\BlogBundle\Entity\ArticleRedactionRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({
 *      0 = "Harentius\BlogBundle\Entity\ArticleRedaction",
 *      1 = "Harentius\BlogBundle\Entity\PageRedaction",
 * })
 */
class ArticleRedaction extends Article
{
    /**
     * @var Article
     *
     * @ORM\OneToOne(targetEntity="Harentius\BlogBundle\Entity\Article")
     */
    private $article;

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $value
     * @return $this
     */
    public function setArticle($value)
    {
        $this->article = $value;

        return $this;
    }
}
