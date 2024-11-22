<?php

namespace App\Markdown\NodeExtractors;

use App\Data\TranslatableUnit;
use App\Extensions\Block\Element\Callout;
use League\CommonMark\Node\Node;

final class CalloutExtractor extends BaseNodeExtractor
{
    public function extractable(Node $node): bool
    {
        return $node instanceof Callout;
    }

    public function extract(Node $node): TranslatableUnit
    {
        /** @var Callout $node */

        $content = $this->renderer->renderNodes($node->children());

        return new TranslatableUnit(
            node: $node,
            type: "CALLOUT: {$node->type}",
            content: $content,
        );
    }
}
