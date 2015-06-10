<?php

namespace Harentius\BlogBundle\Command;

use Harentius\BlogBundle\Entity\AdminUser;
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

        $adminUser = new AdminUser();
        $adminUser
            ->setName('admin')
            ->setPlainPassword('admin')
        ;

        $em->persist($adminUser);
        $em->flush($adminUser);
    }
}
