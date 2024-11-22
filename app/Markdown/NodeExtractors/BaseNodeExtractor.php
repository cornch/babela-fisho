<?php

namespace App\Markdown\NodeExtractors;

use Wnx\CommonmarkMarkdownRenderer\Renderer\MarkdownRenderer;

abstract class BaseNodeExtractor implements Contracts\NodeExtractor
{
    public function __construct(
        protected MarkdownRenderer $renderer,
    ) {
    }
}
