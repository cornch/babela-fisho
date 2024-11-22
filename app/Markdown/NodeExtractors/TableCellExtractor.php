<?php

namespace App\Markdown\NodeExtractors;

use App\Data\TranslatableUnit;
use League\CommonMark\Extension\Table\TableCell;
use League\CommonMark\Node\Node;

final class TableCellExtractor extends BaseNodeExtractor
{
    public function extractable(Node $node): bool
    {
        return $node instanceof TableCell;
    }

    public function extract(Node $node): TranslatableUnit
    {
        if (! $this->extractable($node)) {
            throw new \InvalidArgumentException('Node is not extractable');
        }

        $content = $this->renderer->renderNodes($node->children());

        /** @var TableCell $node */
        return new TranslatableUnit(
            node: $node,
            type: 'TD',
            content: $content,
        );
    }
}
