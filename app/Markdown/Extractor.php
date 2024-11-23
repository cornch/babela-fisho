<?php

namespace App\Markdown;

use App\Data\TranslatableUnit;
use App\Markdown\NodeExtractors\CalloutExtractor;
use App\Markdown\NodeExtractors\Contracts\NodeExtractor;
use App\Markdown\NodeExtractors\CodeExtractor;
use App\Markdown\NodeExtractors\HeadingExtractor;
use App\Markdown\NodeExtractors\HtmlExtractor;
use App\Markdown\NodeExtractors\ListItemExtractor;
use App\Markdown\NodeExtractors\ParagraphExtractor;
use App\Markdown\NodeExtractors\QuoteExtractor;
use App\Markdown\NodeExtractors\TableCellExtractor;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Environment\EnvironmentInterface;
use League\CommonMark\Parser\MarkdownParserInterface;
use League\CommonMark\Node\Node;
use Wnx\CommonmarkMarkdownRenderer\MarkdownRendererExtension;
use function app;
use function array_map;

final class Extractor
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
            fn(string $extractor) => app()->make($extractor),
            $extractors,
        );
    }

    public function addNodeExtractors(NodeExtractor $nodeExtractor): void
    {
        $this->nodeExtractors[] = $nodeExtractor;
    }

    /**
     * @param string $markdown
     *
     * @return array<TranslatableUnit>
     * @throws \League\CommonMark\Exception\CommonMarkException
     */
    public function extract(string $markdown): array
    {
        $document = $this->parser->parse($markdown);
        $walker = $document->walker();
        $units = [];

        while ($event = $walker->next()) {
            if ($event->isEntering()) {
                $node = $event->getNode();
                $unit = $this->extractUnit($node);
                if ($unit !== null) {
                    $units[] = $unit;
                    // if we already have a unit, we can skip the children
                    $walker->resumeAt($node, false);
                }
            }
        }

        return $units;
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
