<?php

namespace App\Markdown;

use App\Data\TranslatableUnit;
use App\Markdown\NodeExtractors\CalloutExtractor;
use App\Markdown\NodeExtractors\Contracts\NodeExtractor;
use App\Markdown\NodeExtractors\FencedCodeExtractor;
use App\Markdown\NodeExtractors\HeadingExtractor;
use App\Markdown\NodeExtractors\HtmlExtractor;
use App\Markdown\NodeExtractors\ListItemExtractor;
use App\Markdown\NodeExtractors\ParagraphExtractor;
use App\Markdown\NodeExtractors\QuoteExtractor;
use App\Markdown\NodeExtractors\TableCellExtractor;
use Gettext\Translations;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Environment\EnvironmentInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Parser\MarkdownParserInterface;
use League\CommonMark\Node\Node;
use Wnx\CommonmarkMarkdownRenderer\MarkdownRendererExtension;

final class Translator
{
    /** @var array<NodeExtractor> */
    private(set) array $nodeExtractors = [];

    public function __construct(
        private MarkdownParserInterface $parser,
    ) {
        $extractors = [
            HeadingExtractor::class,
            ParagraphExtractor::class,
            QuoteExtractor::class,
            FencedCodeExtractor::class,
            TableCellExtractor::class,
            HtmlExtractor::class,
        ];

        $this->nodeExtractors = array_map(
            fn (string $extractor) => app()->make($extractor),
            $extractors,
        );
    }

    public function translate(string $markdown, Translations $translations): Document
    {
        $document = $this->parser->parse($markdown);
        $iterator = $document->iterator();

        foreach ($iterator as $node) {
            if ($node instanceof FencedCode) {
                $literal = $node->getLiteral();
                if (str_ends_with($literal, "\n")) {
                    $node->setLiteral(substr($literal, 0, -1));
                }
            }

            $unit = $this->extractUnit($node);
            if ($unit === null) {
                continue;
            }

            // if we have a unit, we are going to replace it
            $translation = $translations->find(null, $unit->content);

            if ($translation !== null) {
                $translated = $translation->getTranslation();
                $translatedDocument = $this->parser->parse($translated);
                $node->replaceWith($translatedDocument->firstChild());
            }
        }

        return $document;
    }

    private function extractUnit(Node $node): ?TranslatableUnit
    {
        foreach ($this->nodeExtractors as $nodeExtractor) {
            if ($nodeExtractor->extractable($node)) {
                return $nodeExtractor->extract($node);
            }
        }

        return null;
    }
}
