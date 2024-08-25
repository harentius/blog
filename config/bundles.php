<?php

return [
    Harentius\BlogBundle\HarentiusBlogBundle::class => ['all' => true],
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle::class => ['all' => true], // ???
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],

    Presta\SitemapBundle\PrestaSitemapBundle::class => ['all' => true],
    Liip\ImagineBundle\LiipImagineBundle::class => ['all' => true],

    Knp\Bundle\PaginatorBundle\KnpPaginatorBundle::class => ['all' => true],

    Knp\Bundle\MenuBundle\KnpMenuBundle::class => ['all' => true],
    Sonata\CoreBundle\SonataCoreBundle::class => ['all' => true],
    Sonata\BlockBundle\SonataBlockBundle::class => ['all' => true],
    Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle::class => ['all' => true],
    Sonata\AdminBundle\SonataAdminBundle::class => ['all' => true],
    Sonata\TranslationBundle\SonataTranslationBundle::class => ['all' => true],
    Sonata\Doctrine\Bridge\Symfony\Bundle\SonataDoctrineBundle::class => ['all' => true],
];
