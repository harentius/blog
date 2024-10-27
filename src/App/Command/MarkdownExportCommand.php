<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Harentius\BlogBundle\Entity\Article;
use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'blog:markdown:export')]
class MarkdownExportCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('destination', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $destination = $input->getArgument('destination');
        $articles = $this->entityManager->getRepository(Article::class)->findAll();
        $converter = new HtmlConverter();

        foreach ($articles as $article) {
            $html = $article->getText();
            $markdown = '';

            $title = $article->getTitle();
            if ($title) {
                $markdown .= '# ' . $title . "\n\n";
            }

            $metaDescription = $article->getMetaDescription();
            if ($metaDescription) {
                $markdown .= '###### Meta Description: ' . $metaDescription . "\n";
            }

            $metaKeywords = $article->getMetaKeywords();
            if ($metaKeywords) {
                $markdown .= '###### Meta Keywords: ' . $metaKeywords . "\n";
            }

            $publishedAt = $article->getPublishedAt();
            if ($publishedAt) {
                $markdown .= '###### Published at: ' . $publishedAt->format('Y-m-d') . "\n";
            }

            $markdown = $markdown . "\n" . $converter->convert($html);

            $fullPath = $this->getPath($article, $destination);
            $output->writeln('Exporting ' . $fullPath);
            file_put_contents($fullPath, $markdown);
        }

        return 0;
    }

    private function getPath(Article $post, string $destination): string
    {
        $category = $post->getCategory();
        $subPath = '';

        do {
            $subPath = "{$category->getName()}/$subPath";
        } while ($category = $category->getParent());

        $this->ensurePath($destination, $subPath);
        $subPath .= $post->getSlug() . '.md';

        return $destination . '/' . $subPath;
    }

    private function ensurePath(string $prefix, string $path): void
    {
        $fullPath = $prefix . '/' . $path;

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
    }
}
