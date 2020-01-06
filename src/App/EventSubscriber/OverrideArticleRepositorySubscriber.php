<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\ArticleRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Harentius\BlogBundle\Entity\Article;

class OverrideArticleRepositorySubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /**
         * @var ClassMetadata
         */
        $classMetadata = $eventArgs->getClassMetadata();

        if ($classMetadata->getName() !== Article::class) {
            return;
        }

        $classMetadata->customRepositoryClassName = ArticleRepository::class;
    }
}
