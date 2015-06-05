<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\AbstractArticle;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArticleRedactionRepository")
 */
class ArticleRedaction extends AbstractArticle
{
    /**
     * @var Article
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Article")
     */
    private $article;

    /**
     * @return AbstractArticle[]
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
