<?php

namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class LoadBlogData extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return  array(
            __DIR__ . '/blog_data.yml',

        );
    }

    public function getAdminUser()
    {
        return $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:AdminUser')
            ->findOneBy(['name' => 'admin'])
        ;
    }
}
