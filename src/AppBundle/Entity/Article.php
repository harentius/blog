<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\Article as BaseArticle;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArticleRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({
 *      0 = "AppBundle\Entity\Article",
 *      1 = "AppBundle\Entity\Page",
 * })
 */
class Article extends BaseArticle
{

}
