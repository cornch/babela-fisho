<?php

namespace App\Markdown\NodeExtractors;

use App\Data\TranslatableUnit;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Node\Node;

final class CodeExtractor extends BaseNodeExtractor
{
    public function extractable(Node $node): bool
    {
        return $node instanceof FencedCode || $node instanceof IndentedCode;
    }

    public function extract(Node $node): TranslatableUnit
    {
        if (! $this->extractable($node)) {
            throw new \InvalidArgumentException('Node is not extractable');
        }

        /** @var FencedCode|IndentedCode $node*/
        $lang = $node instanceof FencedCode ? $node->getInfo() : null;

        return new TranslatableUnit(
            node: $node,
            type: $lang ? "CODE: {$lang}" : 'CODE',
            content: $node->getLiteral(),
        );
    }
}
