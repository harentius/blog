<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\AbstractArticle;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArticleRepository")
 */
class Article extends AbstractArticle
{

}
