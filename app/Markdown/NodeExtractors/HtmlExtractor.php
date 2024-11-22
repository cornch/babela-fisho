<?php

namespace App\Markdown\NodeExtractors;

use App\Data\TranslatableUnit;
use League\CommonMark\Extension\CommonMark\Node\Block\HtmlBlock;
use League\CommonMark\Node\Node;

final class HtmlExtractor extends BaseNodeExtractor
{
    public function extractable(Node $node): bool
    {
        return $node instanceof HtmlBlock;
    }

    public function extract(Node $node): ?TranslatableUnit
    {
        return null;
    }
}
