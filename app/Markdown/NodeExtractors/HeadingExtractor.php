<?php

namespace App\Markdown\NodeExtractors;

use App\Data\TranslatableUnit;
use App\Markdown\NodeExtractors\Contracts\NodeExtractor;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Node\Node;
use Wnx\CommonmarkMarkdownRenderer\Renderer\MarkdownRenderer;

final class HeadingExtractor extends BaseNodeExtractor
{
    public function extractable(Node $node): bool
    {
        return $node instanceof Heading;
    }

    public function extract(Node $node): TranslatableUnit
    {
        if (! $this->extractable($node)) {
            throw new \InvalidArgumentException('Node is not extractable');
        }

        /** @var Heading $node */
        $markdown = $this->renderer->renderNodes($node->children());

        return new TranslatableUnit(
            node: $node,
            type: 'H' . $node->getLevel(),
            content: $markdown,
        );
    }
}
