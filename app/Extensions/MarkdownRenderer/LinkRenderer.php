<?php

namespace App\Extensions\MarkdownRenderer;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\RegexHelper;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

final class LinkRenderer implements NodeRendererInterface, ConfigurationAwareInterface
{
    private ConfigurationInterface $config;

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        Link::assertInstanceOf($node);

        $attrs = $node->data->get('attributes');

        $allowUnsafeLinks = $this->config->get('allow_unsafe_links');

        $href = $attrs['href'] ?? null;

        if ($allowUnsafeLinks || !RegexHelper::isLinkPotentiallyUnsafe($node->getUrl())) {
            $href = $node->getUrl();
        }

        $title = $node->getTitle() ?? $attrs['title'] ?? null;

        $text = $childRenderer->renderNodes($node->children());

        $href = $this->fixLink($href);

        if ($title) {
            return "[$text]($href \"$title\")";
        }

        return "[$text]($href)";
    }

    private function fixLink(mixed $href)
    {
        // if url contains `{{...}}`, it's a placeholder of laravel's documentation,
        // this type of link will be encoded by league/commonmark, so we need to decode it
        if (str_contains($href, '%7B%7B') && str_contains($href, '%7D%7D')) {
            $href = str_replace(['%7B%7B', '%7D%7D'], ['{{', '}}'], $href);
        }

        return $href;
    }
}
