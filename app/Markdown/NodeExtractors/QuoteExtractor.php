<?php

namespace App\Markdown\NodeExtractors;

use App\Data\TranslatableUnit;
use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
use League\CommonMark\Node\Node;

final class QuoteExtractor extends BaseNodeExtractor
{
    public function extractable(Node $node): bool
    {
        return $node instanceof BlockQuote;
    }

    public function extract(Node $node): TranslatableUnit
    {
        $content = $this->renderer->renderNodes($node->children());

        return new TranslatableUnit(
            node: $node,
            type: 'QUOTE',
            content: $content,
        );
    }
}
