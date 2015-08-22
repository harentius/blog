<?php

namespace Harentius\FolkprogBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Harentius\BlogBundle\Assets\AssetFile;
use Harentius\BlogBundle\Entity\Article;
use Harentius\BlogBundle\Entity\Category;
use Harentius\BlogBundle\Entity\Page;
use Harentius\BlogBundle\Entity\Setting;
use Harentius\BlogBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

class DatabaseDumpCommand extends ContainerAwareCommand
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
     * @var array
     */
    private $data = [];

    /**
     * @var string
     */
    private $siteHost  = 'folkprog.net';

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
            'Tags' => 'dumpTags',
            'Articles' => 'dumpArticles',
            'Pages' => 'dumpPages',
            'Settings' => 'dumpSettings',
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
            $optionValue = unserialize($this->getOption('wpseo_taxonomy_meta'));

            return $optionValue && isset($optionValue['category'][$v['term_id']][$key])
                ? $optionValue['category'][$v['term_id']][$key]
                : null
            ;
        };

        $this->dumpAbstractTaxonomyData('category', Category::class, [
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
        ]);
    }

    /**
     *
     */
    protected function dumpTags()
    {
        $this->dumpAbstractTaxonomyData('post_tag', Tag::class);
    }

    /**
     *
     */
    protected function dumpArticles()
    {
        $getTaxonomy = function ($v, $type) {
            $result = $this->connection->fetchAll("
                SELECT p2a44_terms.term_id
                FROM p2a44_term_relationships
                INNER JOIN p2a44_term_taxonomy ON p2a44_term_relationships.term_taxonomy_id = p2a44_term_taxonomy.term_taxonomy_id
                INNER JOIN p2a44_terms ON p2a44_term_taxonomy.term_id = p2a44_terms.term_id
                WHERE object_id = :object_id
                AND p2a44_term_taxonomy.taxonomy = :type", [':object_id' => $v['ID'], ':type' => $type]
            );

            switch ($type) {
                case 'category':
                    return sprintf('@category-%s', $result[0]['term_id']);
                case 'post_tag':
                    $tags = [];

                    foreach ($result as $tag) {
                        $tags[] = sprintf('@post_tag-%s', $tag['term_id']);
                    }

                    return $tags;
                default:
                    return null;
            }
        };

        $aggregateRating = function ($v, $rating) {
            $result = $this->connection->fetchAssoc("
                SELECT COUNT(rating_id) as result
                FROM p2a44_ratings
                WHERE rating_postid = :post_id
                AND rating_rating = :rating", [':post_id' => $v['ID'], ':rating' => $rating]
            );

            return (int) $result['result'];
        };

        $this->dumpAbstractPostData('post', Article::class, [
            'category' => function ($v) use ($getTaxonomy) {
                return $getTaxonomy($v, 'category');
            },
            'tags' => function ($v) use ($getTaxonomy) {
                return $getTaxonomy($v, 'post_tag');
            },
            'viewsCount' => function ($v) {
                return (int) $this->getPostMeta($v, 'views');
            },
            'likesCount' => function ($v) use ($aggregateRating) {
                return $aggregateRating($v, 1);
            },
            'disLikesCount' => function ($v) use ($aggregateRating) {
                return $aggregateRating($v, -1);
            }
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
     *
     */
    protected function dumpSettings()
    {
        $seoData = unserialize($this->getOption('aioseop_options'));
        $result = [];
        $result[] = [
            'key' => 'project_name',
            'value' => $this->getOption('blogname'),
        ];
        $result[] = [
            'key' => 'homepage_meta_description',
            'value' => $seoData['aiosp_home_description'],
        ];
        $result[] = [
            'key' => 'homepage_meta_keywords',
            'value' => $seoData['aiosp_home_keywords'],
        ];
        $this->data[Setting::class] = $result;
    }

    /**
     * @param $type
     * @param $entityClass
     * @param array $additionalData
     */
    private function dumpAbstractTaxonomyData($type, $entityClass, array $additionalData = [])
    {
        $taxonomy = $this->connection->fetchAll("
            SELECT * FROM p2a44_terms
            INNER JOIN p2a44_term_taxonomy
            ON p2a44_terms.term_id = p2a44_term_taxonomy.term_id
            AND p2a44_term_taxonomy.taxonomy = :type
            ORDER BY parent ASC", [':type' => $type]
        );
        $this->data[$entityClass] = $this->processData($taxonomy, array_merge([
            'name' => 'name',
            'slug' => 'slug',
        ], $additionalData), function ($v) use ($type) {
            return sprintf('%s-%s', $type, $v['term_id']);
        });
    }

    private function getPostMeta($v, $key)
    {
        $result = $this->connection->fetchAssoc("
                SELECT meta_value FROM p2a44_postmeta
                WHERE meta_key = :key
                AND post_id = :post_id", [':key' => $key, ':post_id' => $v['ID']]
        );

        return $result['meta_value'];
    }

    /**
     * @param string $key
     * @return string
     */
    private function getOption($key)
    {
        $result = $this->connection->fetchAssoc("
            SELECT option_value FROM p2a44_options
            WHERE option_name = :option_name", [':option_name' => $key]
        );

        return $result['option_value'];
    }

    /**
     * @param string $type
     * @param string $entityClass
     * @param array $additionalData
     */
    private function dumpAbstractPostData($type, $entityClass, array $additionalData = [])
    {
        $fs = new Filesystem();
        $assetsResolver = $this->getContainer()->get('harentius_blog.assets.resolver');
        $posts = $this->connection->fetchAll("
            SELECT * FROM p2a44_posts
            WHERE post_status IN ('publish', 'draft')
            AND post_type = :type", [':type' => $type]
        );
        $this->data[$entityClass] = $this->processData($posts, array_merge([
            'title' => 'post_title',
            'slug' => 'post_name',
            'text' => function($v) use ($fs, $assetsResolver) {
                // Migrates from syntaxhighlighter.js to highlight.js
                $regExp =
                    '/
                        <pre\s*class="brush:\s*(?<language>[a-zA-Z]*).*?>
                            (?<content>.*?)
                        <\/pre>
                    /xs'
                ;
                $result = sprintf('<p>%s</p>', str_replace(["\r\n\r\n", "\n\n"], '</p><p>', stripcslashes($v['post_content'])));
                $result = preg_replace_callback($regExp, function($matches) {
                    return sprintf(
                        '<pre><code class="%s">%s</code></pre>',
                        $matches['language'],
                        $matches['content']
                    );
                }, $result);

                //Dumping mp3s
                $regExp = '/\[ca_audio\s*url=\"(?<url>.*?)\".*\]/';
                $result = preg_replace_callback($regExp, function($matches) use ($assetsResolver) {
                    $path = $this->loadFile($matches['url'], $this->directory . '/tmp/');

                    return sprintf(
                    // Silent missing IDE warning
                        '<audio src=' . '"%s" controls="controls"></audio>',
                        $assetsResolver->pathToUri(realpath($path))
                    );
                }, $result);

                $processAttributes = function ($imageNode) {
                    $currentClass = $imageNode->getAttribute('class');
                    $newStyle = '';

                    if (strpos($currentClass, 'alignleft') !== false) {
                        $newStyle .= ' float:left;';
                    }

                    if (strpos($currentClass, 'alignright') !== false) {
                        $newStyle .= ' float:right;';
                    }

                    if (strpos($currentClass, 'aligncenter') !== false) {
                        $newStyle .= ' display:block;margin: 15px auto;';
                    }

                    if ($newStyle) {
                        $imageNode->setAttribute('style', trim($imageNode->getAttribute('style') . ';' . $newStyle));
                    }

                    $imageNode->removeAttribute('class');

                    if ($imageNode->getAttribute('width') > 826) {
                        $imageNode->removeAttribute('width');
                    }

                    $imageNode->removeAttribute('height');
                };

                //Dumping captioned images
                $regExp = '/\[caption.*align=\"(?<align>.*?)\".*width=\"(?<width>.*?)\".*\](?<content><.*\/>)(?<caption>.*?)\[\/caption\]/';
                $result = preg_replace_callback($regExp, function(&$matches) {
                    if (isset($matches['content'])) {
                        $crawler = new Crawler();
                        $crawler->addHtmlContent($matches['content'], 'UTF-8');
                        $crawler->filter('img')->each(function ($node) {
                            /** @var Crawler $node */
                            $imageNode = $node->getNode(0);
                            $imageNode->removeAttribute('style');
                        });
                        $matches['img'] = $crawler->filter('body')->html();
                    } else {
                        $matches['img'] = '';
                    }

                    if (!isset($matches['width'])) {
                        $matches['width'] = 'auto';
                    } else {
                        $matches['width'] = ((int) $matches['width']) . 'px';
                    }

                    if (!isset($matches['align'])) {
                        $matches['align'] = '';
                    }

                    if (!isset($matches['caption'])) {
                        $matches['caption'] = '';
                    }

                    return sprintf(
                    // Silent missing IDE warning
                        '<figure %s>%s<figcaption>%s</figcaption></figure>',
                        "style=\"width:{$matches['width']}\" class=\"{$matches['align']}\"",
                        trim($matches['img']),
                        trim($matches['caption'])
                    );
                }, $result);

                $crawler = new Crawler();
                $crawler->addHtmlContent($result, 'UTF-8');

                // Processing images
                $crawler->filter('figure')->each(function ($node) use ($processAttributes) {
                    $processAttributes($imageNode = $node->getNode(0));
                });

                $crawler->filter('img')->each(function ($node) use ($fs, $assetsResolver, $processAttributes) {
                    /** @var Crawler $node */
                    $imageNode = $node->getNode(0);
                    $oldSrc = $imageNode->getAttribute('src');
                    $path = $this->loadFile($oldSrc, $this->directory . '/tmp/');
                    $newSrc = $assetsResolver->pathToUri(realpath($path));
                    $imageNode->setAttribute('src', $newSrc);
                    $processAttributes($imageNode);
                    $parentNode = $imageNode->parentNode;

                    if ($parentNode->tagName === 'a' && $parentNode->getAttribute('href') === $imageNode) {
                        $parentNode->setAttribute('href', $newSrc);
                    }
                });

                // Processing iframes
                $crawler->filter('iframe')->each(function ($node) {
                    /** @var Crawler $node */
                    $iframeNode = $node->getNode(0);
                    $newStyle = $iframeNode->getAttribute('style') . ' width:100%;height:100%;position:absolute;top:0;left:0;';
                    $iframeNode->setAttribute('style', $newStyle);
                    $iframeNode->removeAttribute('width');
                    $iframeNode->removeAttribute('height');
                    $newStyle = $iframeNode->parentNode->getAttribute('style') . ' width:100%;height:auto;position:relative;padding-bottom:60%;';
                    $iframeNode->parentNode->setAttribute('style', $newStyle);
                });

                //Dumping archives
                $crawler->filter('a')->each(function ($node) use ($fs, $assetsResolver) {
                    /** @var Crawler $node */
                    $linkNode = $node->getNode(0);
                    $oldSrc = $linkNode->getAttribute('href');

                    $pathInfo = pathinfo($oldSrc);
                    $parsedUrl = parse_url($oldSrc);

                    if (
                        (isset($pathInfo['extension']) && in_array($pathInfo['extension'], ['rar', 'zip', 'tar', 'gz']))
                            &&
                        (!isset($parsedUrl['host']) || $parsedUrl['host'] === $this->siteHost)
                    ) {
                        $path = $this->loadFile($oldSrc, $this->directory . '/tmp/');
                        $newSrc = $assetsResolver->pathToUri(realpath($path));
                        $linkNode->setAttribute('href', $newSrc);
                    }
                });

                $result = $crawler->filter('body')->html();

                // Fixing broken tags
                $result = str_replace('<p><!--more--></p>', '<!--more-->', $result);

                return $result;
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
            'createdAt' => 'post_modified',
            'updatedAt' => 'post_modified',
            'metaDescription' => function ($v) {
                return $this->getPostMeta($v, '_yoast_wpseo_metadesc');
            },
            'metaKeywords' => function ($v) {
                return $this->getPostMeta($v, '_yoast_wpseo_metakeywords');
            },
        ], $additionalData), function ($v) {
            return sprintf('post-%s', $v['ID']);
        }, function ($v) {
            return (bool) $v['post_title'] && $v['post_content'] && $v['post_name'] !== 'sitemap';
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

    /**
     * @param string $uri
     * @param string $targetDir
     * @return string
     * @throws \Exception
     */
    private function loadFile($uri, $targetDir)
    {
        static $fs = null;

        if ($fs === null) {
            $fs = new Filesystem();
        }

        $filename = pathinfo($uri, PATHINFO_BASENAME);
        $targetFile = $targetDir . $filename;

        if (!$fs->exists($targetFile)) {
            $targetDirectory = dirname($targetFile);

            if (!$fs->exists($targetDirectory)) {
                $fs->mkdir($targetDirectory, 0777);
            }

            $url =  ltrim($uri, '/');

            if (strpos($uri, 'http://') === false) {
                $url = 'http://' . $this->siteHost . '/' . $url;
            }

            $fileData = @file_get_contents($url);

            if (!$fileData) {
                throw new \RuntimeException(sprintf("Unable to download file '%s'", $url));
            }

            if (!@file_put_contents($targetFile, $fileData)) {
                throw new \RuntimeException(sprintf("Unable to save file '%s' under '%s'", $url, $targetFile));
            }
        }

        $file = new AssetFile(new File($targetFile));
        $type = $file->getType();

        if (!$type) {
            throw new \LogicException(sprintf("Unsupported file %s", $file->getOriginalName()));
        }

        $finalPath = sprintf('%s/../web/assets/%ss/%s',
            $this->getContainer()->getParameter('kernel.root_dir'),
            $type,
            pathinfo($targetFile, PATHINFO_BASENAME)
        );
        $fs->copy($targetFile, $finalPath);

        return $finalPath;
    }
}
