<?php

namespace App\Extensions\MarkdownRenderer;

use League\CommonMark\Extension\Strikethrough\Strikethrough;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

final class StrikethroughRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        Strikethrough::assertInstanceOf($node);

        return '~~' . $childRenderer->renderNodes($node->children()) . '~~';
    }
}
