<?php

namespace App\Markdown\NodeExtractors;

use App\Data\TranslatableUnit;
use App\Markdown\NodeExtractors\Contracts\NodeExtractor;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Node;
use Wnx\CommonmarkMarkdownRenderer\Renderer\MarkdownRenderer;

final class ParagraphExtractor extends BaseNodeExtractor
{
    public function extractable(Node $node): bool
    {
        return $node instanceof Paragraph;
    }

    public function extract(Node $node): TranslatableUnit
    {
        if (! $this->extractable($node)) {
            throw new \InvalidArgumentException('Node is not extractable');
        }

        $content = $this->renderer->renderNodes([$node]);

        return new TranslatableUnit(
            node: $node,
            type: 'P',
            content: $content,
        );
    }
}
