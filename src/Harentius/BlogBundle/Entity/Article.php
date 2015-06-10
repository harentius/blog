<?php

namespace Harentius\BlogBundle\Entity;

use Harentius\BlogBundle\Entity\Base\Article as BaseArticle;
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

}
