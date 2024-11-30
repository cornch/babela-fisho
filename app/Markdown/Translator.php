<?php

namespace App\Markdown;

use App\Data\TranslatableUnit;
use App\Markdown\NodeExtractors\Contracts\NodeExtractor;
use App\Markdown\NodeExtractors\CodeExtractor;
use App\Markdown\NodeExtractors\HeadingExtractor;
use App\Markdown\NodeExtractors\HtmlExtractor;
use App\Markdown\NodeExtractors\ListItemExtractor;
use App\Markdown\NodeExtractors\ParagraphExtractor;
use App\Markdown\NodeExtractors\QuoteExtractor;
use App\Markdown\NodeExtractors\TableCellExtractor;
use Gettext\Translations;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Parser\MarkdownParserInterface;
use League\CommonMark\Node\Node;

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
            CodeExtractor::class,
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

            if ($translation === null) {
                continue;
            }

            $translated = $translation->getTranslation();
            if ($node instanceof FencedCode || $node instanceof IndentedCode) {
                $node->setLiteral($translated);
                continue;
            }

            $translatedDocument = $this->parser->parse($translated);
            $translatedNodes = [$translatedDocument->firstChild()];
            if ($translatedNodes[0] instanceof Paragraph) {
                $translatedNodes = $translatedNodes[0]->children();
            }
            $node->detachChildren();
            foreach ($translatedNodes as $translatedNode) {
                $node->appendChild($translatedNode);
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
