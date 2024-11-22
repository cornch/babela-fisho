<?php

namespace App\Markdown\NodeExtractors\Contracts;

use App\Data\TranslatableUnit;
use League\CommonMark\Node\Node;

interface NodeExtractor
{
    public function extractable(Node $node): bool;

    public function extract(Node $node): ?TranslatableUnit;
}
