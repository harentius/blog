<?php

namespace Harentius\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Harentius\BlogBundle\Entity\Base\AbstractPost;
use Harentius\BlogBundle\Entity\Base\PageChangeableFieldsEntityTrait;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Harentius\BlogBundle\Entity\PageRepository")
 */
class Page extends AbstractPost
{
    use PageChangeableFieldsEntityTrait;

    /**
     *
     */
    public function __construct()
    {
        $this->isPublished = false;
    }
}
