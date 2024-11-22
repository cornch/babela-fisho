<?php

namespace App\Extensions\MarkdownRenderer;

use App\Extensions\Block\Element\Callout;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

final class CalloutRenderer implements NodeRendererInterface
{
    /**
     * @param Callout $node
     * @param ChildNodeRendererInterface $childRenderer
     *
     * @return string
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        Callout::assertInstanceOf($node);

        return "> [!{$node->type}]  \n> " . $childRenderer->renderNodes($node->children());
    }
}
