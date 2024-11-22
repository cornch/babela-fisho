<?php

namespace App\Data;

use League\CommonMark\Node\Node;
use Spatie\LaravelData\Resource;

final class TranslatableUnit extends Resource
{
    public ?int $line {
        get {
            return $this->node->getStartLine();
        }
    }

    public function __construct(
        public Node $node,
        public string $type,
        public string $content {
            set(string $value) {
                if (str_ends_with($value, "\n")) {
                    $this->content = substr($value, 0, -1);
                } else {
                    $this->content = $value;
                }
            }
        },
    ) {
    }
}
