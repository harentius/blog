<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Harentius\BlogBundle\Entity\Article;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class Version20191226204941 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function up(Schema $schema) : void
    {
        $this->addSql('SELECT 1');
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $articleRepository = $em->getRepository(Article::class);

        foreach ($articleRepository->findAll() as $article) {
            $article->setText(str_replace('<!--more-->', '<p class="read-more">&nbsp;</p>', $article->getText()));
        }

        $em->flush();
    }

    public function down(Schema $schema) : void
    {
    }
}
