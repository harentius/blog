<?php

namespace Harentius\FolkprogBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\Category;
use Harentius\BlogBundle\Entity\Page;
use Harentius\BlogBundle\Entity\Tag;
use Harentius\FolkprogBundle\Utils\FieldsCopier;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class DatabaseLoadCommand extends ContainerAwareCommand
{
    /**
     * @var array
     */
    private $references;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FieldsCopier
     */
    private $fieldsCopier;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('folkprog:database:load')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED)
            ->addOption('dump-file', 'f', InputOption::VALUE_REQUIRED, '', 'dump.yml')
            ->addOption('no-sql-logger', null, InputOption::VALUE_NONE)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getOption('dir');
        $dumpFile = sprintf('%s/%s', $directory, $input->getOption('dump-file'));

        if (!file_exists($dumpFile)) {
            throw new \InvalidArgumentException(sprintf("File '%s' not found", $dumpFile));
        }

        $this->references = [];

        $this->fieldsCopier = new FieldsCopier();
        $this->fieldsCopier->setIgnoreMissingSourceFields(true);
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $rootDir = $this->getContainer()->getParameter('kernel.root_dir') . '/..';
        $importAssetsDir = $directory . '/assets';
        $assetsDir = $rootDir . '/web/assets';
        $this->fs = new Filesystem();
        $this->createDirectory($importAssetsDir);

        $rawData = Yaml::parse($dumpFile);
        /** @var Connection $connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        if ($input->getOption('no-sql-logger')) {
            $backupSQLLogger = $connection->getConfiguration()->getSQLLogger();
            $connection->getConfiguration()->setSQLLogger(null);
        }

        $processors = [
            'Categories' => 'loadCategories',
            'Tags' => 'loadTags',
            'Articles' => 'loadArticles',
            'Pages' => 'loadPages',
        ];

        foreach ($processors as $name => $method) {
            $output->write(sprintf('<info>Importing %s data...</info>', $name));
            $this->{$method}($rawData);
            $output->writeln(sprintf('<info> done (loaded %s items)</info>', $name));
        }

        $output->writeln("<info>Loaded data from '$dumpFile'</info>");

        if ($input->getOption('no-sql-logger') && isset($backupSQLLogger)) {
            $connection->getConfiguration()->setSQLLogger($backupSQLLogger);
        }
    }

    /**
     * @param array $rawData
     * @return int
     */
    protected function loadCategories($rawData)
    {
        $count = 0;

        foreach ($rawData[Category::class] as $id => $categoryData) {
            $category = new Category();

            $this->fieldsCopier->copy(
                ['name', 'slug', 'metaDescription', 'metaKeywords'],
                $categoryData,
                $category
            );

            if ($categoryData['parent']) {
                $category->setParent($this->getReference($categoryData['parent']));
            }

            $this->em->persist($category);
            $this->setReference($id, $category);
            $count++;
        }

        $this->em->flush();

        return $count;
    }


    /**
     * @param array $rawData
     * @return int
     */
    protected function loadTags($rawData)
    {
        $count = 0;

        foreach ($rawData[Tag::class] as $id => $tagData) {
            $tag = new Tag();

            $this->fieldsCopier->copy(
                ['name', 'slug'],
                $tagData,
                $tag
            );

            $this->em->persist($tag);
            $this->setReference($id, $tag);
            $count++;
        }

        $this->em->flush();

        return $count;
    }

    /**
     * @param array $rawData
     * @return int
     */
    protected function loadArticles($rawData)
    {
        return $this->loadAbstractPostData($rawData, Article::class);
    }

    /**
     * @param array $rawData
     * @return int
     */
    protected function loadPages($rawData)
    {
        return $this->loadAbstractPostData($rawData, Page::class, false);
    }

    /**
     * @param $rawData
     * @param $entityClass
     * @param bool|true $categoryRequired
     * @return int
     */
    private function loadAbstractPostData($rawData, $entityClass, $categoryRequired = true)
    {
        $count = 0;
        $adminUser = $this->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('HarentiusBlogBundle:AdminUser')
            ->findOneBy(['username' => 'admin'])
        ;

        if (!$adminUser) {
            throw new \LogicException('Admin user now found');
        }

        foreach ($rawData[$entityClass] as $id => $articleData) {
            /** @var Article|Page $article */
            $article = new $entityClass();

            $this->fieldsCopier->copy(
                ['title', 'slug', 'text', 'isPublished', 'metaDescription', 'metaKeywords'],
                $articleData,
                $article
            );

            if ($articleData['publishedAt']) {
                $article->setpublishedAt(new \DateTime($articleData['publishedAt']));
            }

            if ($categoryRequired) {
                $article->setCategory($this->getReference($articleData['category']));
            }

            if (isset($articleData['tags'])) {
                foreach ($articleData['tags'] as $tag) {
                    $article->addTag($this->getReference($tag));
                }
            }

            $article->setAuthor($adminUser);
            $this->em->persist($article);
            $this->setReference($id, $article);
            $count++;
        }

        $this->em->flush();

        return $count;
    }

    /**
     * @param string $path
     */
    private function createDirectory($path)
    {
        if (!$this->fs->exists($path)) {
            $this->fs->mkdir($path, 0777);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    private function setReference($name, $value)
    {
        if (isset($this->references[$name])) {
            throw new \RuntimeException(sprintf("Reference '%s' already defined", $name));
        }

        $this->references[$name] = $value;
    }

    /**
     * @param string $reference
     * @return mixed
     */
    private function getReference($reference)
    {
        $reference = (string) $reference;

        if (!$reference || strlen($reference) < 2 || $reference[0] !== '@') {
            throw new \RuntimeException(sprintf("Invalid reference id '%s', is should be string and start from '@'", $reference));
        }

        $reference = substr($reference, 1);

        if (!isset($this->references[$reference])) {
            throw new \RuntimeException(sprintf("Reference '%s' not found", $reference));
        }

        return $this->references[$reference];
    }
}
