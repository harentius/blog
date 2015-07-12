<?php

namespace Harentius\FolkprogBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\Category;
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
            ->setName('blog:database:dump')
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
            'Posts' => 'dumpPosts',
        ];

        foreach ($dumps as $name => $method) {
            $output->writeln(sprintf('<info>Dumping %s</info>', $name));
            $this->{$method}();
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
            AND p2a44_term_taxonomy.taxonomy = 'category'"
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
    protected function dumpPosts()
    {
        $getMeta = function ($v, $key) {
            $result = $this->connection->fetchAssoc("
                SELECT meta_value FROM p2a44_postmeta
                WHERE meta_key = :key
                AND post_id = :post_id", [':key' => $key, ':post_id' => $v['ID']]
            );

            return $result['meta_value'];
        };

        $posts = $this->connection->fetchAll("SELECT * FROM p2a44_posts WHERE post_status IN ('publish', 'draft')");
        $this->data[Article::class] = $this->processData($posts, [
            'title' => 'post_title',
            'slug' => 'post_name',
            'text' => 'post_content',
            'isPublished' => function ($v) {
                return $v['post_status'] === 'publish';
            },
            'publishedAt' => 'post_date_gmt',
            'category' => function ($v) {
                $result = $this->connection->fetchAssoc("
                    SELECT term_taxonomy_id
                    FROM p2a44_term_relationships
                    WHERE object_id = :object_id", [':object_id' => $v['ID']]
                );

                return sprintf('@category-%s', $result['term_taxonomy_id']);
            },
            'metaDescription' => function ($v) use ($getMeta) {
                return $getMeta($v, '_yoast_wpseo_metadesc');
            },
            'metaKeywords' => function ($v) use ($getMeta) {
                return $getMeta($v, '_yoast_wpseo_metakeywords');
            },
        ], function ($v) {
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
