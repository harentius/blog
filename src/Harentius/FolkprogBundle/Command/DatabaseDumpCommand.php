<?php

namespace Harentius\FolkprogBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\Category;
use Harentius\BlogBundle\Entity\Page;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class DatabaseDumpCommand extends Command
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var array
     */
    private $data = [];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('folkprog:database:dump')
            ->addOption('db-name', null, InputOption::VALUE_REQUIRED)
            ->addOption('db-host', null, InputOption::VALUE_OPTIONAL, '', 'localhost')
            ->addOption('db-user', null, InputOption::VALUE_REQUIRED)
            ->addOption('db-password', null, InputOption::VALUE_OPTIONAL)
            ->addOption('dir', null, InputOption::VALUE_REQUIRED)
            ->addOption('dump-file', null, InputOption::VALUE_REQUIRED, '', 'dump.yml')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->directory = $input->getOption('dir');
        $dumpFile = sprintf('%s/%s', $this->directory, $input->getOption('dump-file'));

        if (file_exists($dumpFile)) {
            $output->writeln("<info>Dump file '$dumpFile' already exists, skipping</info>");

            return;
        }

        $this->connection = DriverManager::getConnection([
            'dbname' => $input->getOption('db-name'),
            'host' => $input->getOption('db-host'),
            'user' => $input->getOption('db-user'),
            'password' => $input->getOption('db-password'),
            'driver' => 'pdo_mysql',
            'charset' => 'utf8',
        ]);

        $dumps = [
            'Categories' => 'dumpCategories',
            'Articles' => 'dumpArticles',
            'Pages' => 'dumpPages',
        ];

        foreach ($dumps as $name => $method) {
            $output->write(sprintf('<info>Dumping %s...</info>', $name));
            $this->{$method}();
            $output->writeln(sprintf('<info> done</info>', $name));
        }

        file_put_contents($dumpFile, Yaml::dump($this->data, 100));
        $output->writeln("<info>Migration file '$dumpFile' successfully created</info>");
    }

    /**
     *
     */
    protected function dumpCategories()
    {
        $getMeta = function ($v, $key) {
            $result = $this->connection->fetchAssoc("
                SELECT option_value FROM p2a44_options
                WHERE option_name = 'wpseo_taxonomy_meta'"
            );

            $optionValue = unserialize($result['option_value']);

            return $optionValue && isset($optionValue['category'][$v['term_id']][$key])
                ? $optionValue['category'][$v['term_id']][$key]
                : null
            ;
        };

        $categories = $this->connection->fetchAll("
            SELECT * FROM p2a44_terms
            INNER JOIN p2a44_term_taxonomy
            ON p2a44_terms.term_id = p2a44_term_taxonomy.term_id
            AND p2a44_term_taxonomy.taxonomy = 'category'
            ORDER BY parent ASC"
        );
        $this->data[Category::class] = $this->processData($categories, [
            'name' => 'name',
            'slug' => 'slug',
            'parent' => function ($v) {
                $result = $this->connection->fetchAssoc("
                    SELECT parent FROM p2a44_terms
                    INNER JOIN p2a44_term_taxonomy
                    ON p2a44_terms.term_id = p2a44_term_taxonomy.term_id
                    WHERE p2a44_terms.term_id = :term_id", [':term_id' => $v['term_id']]
                );

                return $result && $result['parent']
                    ? sprintf('@category-%s', $result['parent'])
                    : null
                ;
            },
            'metaDescription' => function ($v) use ($getMeta) {
                return $getMeta($v, 'wpseo_desc');
            },
            'metaKeywords' => function ($v) use ($getMeta) {
                return $getMeta($v, 'wpseo_metakey');
            },
        ], function ($v) {
            return sprintf('category-%s', $v['term_id']);
        });
    }

    /**
     *
     */
    protected function dumpArticles()
    {
        $this->dumpAbstractPostData('post', Article::class, [
            'category' => function ($v) {
                $result = $this->connection->fetchAssoc("
                    SELECT p2a44_terms.term_id
                    FROM p2a44_term_relationships
                    INNER JOIN p2a44_term_taxonomy ON p2a44_term_relationships.term_taxonomy_id = p2a44_term_taxonomy.term_taxonomy_id
                    INNER JOIN p2a44_terms ON p2a44_term_taxonomy.term_id = p2a44_terms.term_id
                    WHERE object_id = :object_id
                    AND p2a44_term_taxonomy.taxonomy = 'category'", [':object_id' => $v['ID']]
                );

                return sprintf('@category-%s', $result['term_id']);
            },
        ]);
    }

    /**
     *
     */
    protected function dumpPages()
    {
        $this->dumpAbstractPostData('page', Page::class, [
            'isPublished' => function () {
                return true;
            },
        ]);
    }

    /**
     * @param string $type
     * @param string $entityClass
     * @param array $additionalData
     */
    private function dumpAbstractPostData($type, $entityClass, array $additionalData = [])
    {
        $getMeta = function ($v, $key) {
            $result = $this->connection->fetchAssoc("
                SELECT meta_value FROM p2a44_postmeta
                WHERE meta_key = :key
                AND post_id = :post_id", [':key' => $key, ':post_id' => $v['ID']]
            );

            return $result['meta_value'];
        };

        $posts = $this->connection->fetchAll("
            SELECT * FROM p2a44_posts
            WHERE post_status IN ('publish', 'draft')
            AND post_type = :type", [':type' => $type]
        );
        $this->data[$entityClass] = $this->processData($posts, array_merge([
            'title' => 'post_title',
            'slug' => 'post_name',
            'text' => function($v) {
                return stripcslashes($v['post_content']);
            },
            'isPublished' => function ($v) {
                return $v['post_status'] === 'publish';
            },
            'publishedAt' => function ($v) {
                if ($v['post_date_gmt'] !== '0000-00-00 00:00:00') {
                    return $v['post_date_gmt'];
                } elseif ($v['post_date'] !== '0000-00-00 00:00:00') {
                    return $v['post_date'];
                } else {
                    return null;
                }
            },
            'metaDescription' => function ($v) use ($getMeta) {
                return $getMeta($v, '_yoast_wpseo_metadesc');
            },
            'metaKeywords' => function ($v) use ($getMeta) {
                return $getMeta($v, '_yoast_wpseo_metakeywords');
            },
        ], $additionalData), function ($v) {
            return sprintf('post-%s', $v['ID']);
        }, function ($v) {
            return (bool) $v['post_title'] && $v['post_content'];
        });
    }

    /**
     * @param array $input
     * @param array $fieldsMap
     * @param string $indexField
     * @param callable|null $fieldCallback
     * @return array
     */
    private function processData($input, $fieldsMap, $indexField, $fieldCallback = null)
    {
        $output = [];

        foreach ($input as $row) {
            if ($fieldCallback !== null && !$fieldCallback($row)) {
                continue;
            }

            $i = $indexField instanceof \Closure ? $indexField($row) : $row[$indexField];

            foreach ($fieldsMap as $newKey => $oldKey) {
                $rawValue = $oldKey instanceof \Closure ? $oldKey($row) : $row[$oldKey];
                $output[$i][$newKey] = $rawValue;
            }
        }

        return $output;
    }
}
