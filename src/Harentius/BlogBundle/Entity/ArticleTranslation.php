<?php

namespace Harentius\BlogBundle\Entity;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(indexes={
 *      @ORM\Index(name="article_translation_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class ArticleTranslation extends AbstractTranslation
{

}
