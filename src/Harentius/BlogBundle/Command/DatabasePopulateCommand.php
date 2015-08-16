<?php

namespace Harentius\BlogBundle\Command;

use Harentius\BlogBundle\Entity\AdminUser;
use Harentius\BlogBundle\Entity\Setting;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabasePopulateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('blog:database:populate')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $encoder = $this->getContainer()->get('security.password_encoder');
        $entitiesForPersisting = [];

        // Admin user
        $adminUser = new AdminUser();
        $adminUser
            ->setUsername('admin')
            ->setPassword($encoder->encodePassword($adminUser, 'admin'))
        ;

        $entitiesForPersisting[] = $adminUser;

        // Settings
        $entitiesForPersisting = array_merge($entitiesForPersisting, [
            (new Setting())
                ->setKey('project_name')
                ->setName('Project Name')
                ->setValue('Simple Symfony Blog'),

            (new Setting())
                ->setKey('homepage_meta_description')
                ->setName('Homepage Meta Description')
                ->setValue('Simple Symfony Blog description'),

            (new Setting())
                ->setKey('homepage_meta_keywords')
                ->setName('Homepage Meta Keywords')
                ->setValue('symfony blog'),
        ]);

        foreach ($entitiesForPersisting as $entity) {
            $em->persist($entity);
            $em->flush($entity);
        }
    }
}
