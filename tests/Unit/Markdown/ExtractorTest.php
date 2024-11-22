<?php

use App\Data\TranslatableUnit;
use App\Markdown\Extractor;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Parser\MarkdownParser;

dataset('Extractable Contents', [
    'H1' => [
        'code' => '# Hello',
        'extracted' => [[
            'type' => 'H1',
            'content' => 'Hello'
        ]],
    ],
    'H2' => [
        'code' => '## Hello',
        'extracted' => [[
            'type' => 'H2',
            'content' => 'Hello'
        ]],
    ],
    'H3' => [
        'code' => '### Hello',
        'extracted' => [[
            'type' => 'H3',
            'content' => 'Hello'
        ]],
    ],
    'H4' => [
        'code' => '#### Hello',
        'extracted' => [[
            'type' => 'H4',
            'content' => 'Hello'
        ]],
    ],
    'H5' => [
        'code' => '##### Hello',
        'extracted' => [[
            'type' => 'H5',
            'content' => 'Hello'
        ]],
    ],
    'H6' => [
        'code' => '###### Hello',
        'extracted' => [[
            'type' => 'H6',
            'content' => 'Hello'
        ]],
    ],
    'Paragraph' => [
        'code' => 'Hello',
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Paragraph with multiple lines' => [
        'code' => "Hello\nWorld",
        'extracted' => [[
            'type' => 'P',
            'content' => "Hello\nWorld"
        ]],
    ],
    'Blockquote' => [
        'code' => '> Hello',
        'extracted' => [[
            'type' => 'QUOTE',
            'content' => 'Hello'
        ]],
    ],
    'Blockquote with multiple lines' => [
        'code' => "> Hello\n> World",
        'extracted' => [[
            'type' => 'QUOTE',
            'content' => "Hello\nWorld"
        ]],
    ],
    'Callout - Note' => [
        'code' => "> [!NOTE]\n> Hello",
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Tip' => [
        'code' => "> [!TIP]  \n> Hello",
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Warning' => [
        'code' => "> [!WARNING]  \n> Hello",
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Important' => [
        'code' => "> [!IMPORTANT]  \n> Hello",
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Caution' => [
        'code' => "> [!CAUTION]  \n> Hello",
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Note on same line' => [
        'code' => '> [!NOTE] Hello',
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Tip on same line' => [
        'code' => '> [!TIP] Hello',
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Warning on same line' => [
        'code' => '> [!WARNING] Hello',
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Important on same line' => [
        'code' => '> [!IMPORTANT] Hello',
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Callout - Caution on same line' => [
        'code' => '> [!CAUTION] Hello',
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Unordered List' => [
        'code' => "- Hello",
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Unordered List with multiple lines' => [
        'code' => "- Hello\n  World\n- Saluton, mondo",
        'extracted' => [
            [
                'type' => 'P',
                'content' => "Hello\nWorld"
            ],
            [
                'type' => 'P',
                'content' => "Saluton, mondo"
            ],
        ],
    ],
    'Unordered List with sub-items' => [
        'code' => "- Hello\n  - World",
        'extracted' => [
            [
                'type' => 'P',
                'content' => "Hello"
            ],
            [
                'type' => 'P',
                'content' => "World"
            ],
        ],
    ],
    'Ordered List' => [
        'code' => "1. Hello",
        'extracted' => [[
            'type' => 'P',
            'content' => 'Hello'
        ]],
    ],
    'Ordered List with multiple lines' => [
        'code' => "1. Hello\n2. World",
        'extracted' => [
            [
                'type' => 'P',
                'content' => "Hello"
            ],
            [
                'type' => 'P',
                'content' => "World"
            ],
        ],
    ],
    'Ordered List with sub-items' => [
        'code' => "1. Hello\n   1. World",
        'extracted' => [
            [
                'type' => 'P',
                'content' => 'Hello'
            ],
            [
                'type' => 'P',
                'content' => 'World'
            ],
        ],
    ],
    'List with block elements inside' => [
        'code' => <<<MD
            - Hello
              ```php
              echo 'World';
              ```
              - Saluton, mondo
            MD,
        'extracted' => [
            [
                'type' => 'P',
                'content' => 'Hello'
            ],
            [
                'type' => 'CODE: php',
                'content' => "echo 'World';"
            ],
            [
                'type' => 'P',
                'content' => 'Saluton, mondo'
            ],
        ],

    ],
    'Code Block' => [
        'code' => "```php\nHello\n```",
        'extracted' => [[
            'type' => 'CODE: php',
            'content' => "Hello"
        ]],
    ],
    'Code Block with multiple lines' => [
        'code' => "```php\nHello\nWorld\n```",
        'extracted' => [[
            'type' => 'CODE: php',
            'content' => "Hello\nWorld"
        ]],
    ],
    'Table' => [
        'code' => "| Hello | World |\n|-------|-------|\n| Foo   | Bar   |",
        'extracted' => [
            [
                'type' => 'TD',
                'content' => 'Hello'
            ],
            [
                'type' => 'TD',
                'content' => 'World'
            ],
            [
                'type' => 'TD',
                'content' => 'Foo'
            ],
            [
                'type' => 'TD',
                'content' => 'Bar'
            ],
        ],
    ],
    'HTML Block' => [
        'code' => "<div>Hello</div>",
        'extracted' => [],
    ],
]);

it('extracts translatable units', function ($code, $extracted) {
    $extractor = app()->make(Extractor::class);
    $extractor->useDefaultNodeExtractors();
    $units = $extractor->extract($code);

    if (empty($extracted)) {
        expect($units)->toBeEmpty();
        return;
    }

    foreach ($extracted as $i => $expectedUnit) {
        expect($units[$i])->toBeInstanceOf(TranslatableUnit::class)
            ->and($units[$i]->type)->toBe($expectedUnit['type'])
            ->and($units[$i]->content)->toBe($expectedUnit['content']);
    }
})->with('Extractable Contents');

it('extract a complete markdown file', function () {
    $markdown = <<<MD
    # Hello

    Hello, world!

    - List Item
      ```php
      echo 'With code block';
      ```
      - And a sub-item
    - Oops, another list item

    ## Another section

    ![Image](https://example.com/image.png)

    And a [link](https://example.com), with ![inline image](https://example.com/image.png).

    ```php
    echo 'Another code block';
    ```

    ````php
    echo 'Also code block, but with 4 backticks';
    ````

    | Table | Content |
    |-------|---------|
    | Foo   | Bar     |

    > A blockquote

    > Another type of blockquote
    > > And another line

    > [!NOTE]
    > This is a note

    [Link](https://example.com)

    1. Ordered List
    2. With multiple lines
    MD;

    $expected = [
        ['type' => 'H1', 'content' => 'Hello'],
        ['type' => 'P', 'content' => 'Hello, world!'],
        ['type' => 'P', 'content' => 'List Item'],
        ['type' => 'CODE: php', 'content' => "echo 'With code block';"],
        ['type' => 'P', 'content' => 'And a sub-item'],
        ['type' => 'P', 'content' => 'Oops, another list item'],
        ['type' => 'H2', 'content' => 'Another section'],
        ['type' => 'P', 'content' => '![Image](https://example.com/image.png)'],
        ['type' => 'P', 'content' => 'And a [link](https://example.com), with ![inline image](https://example.com/image.png).'],
        ['type' => 'CODE: php', 'content' => "echo 'Another code block';"],
        ['type' => 'CODE: php', 'content' => "echo 'Also code block, but with 4 backticks';"],
        ['type' => 'TD', 'content' => 'Table'],
        ['type' => 'TD', 'content' => 'Content'],
        ['type' => 'TD', 'content' => 'Foo'],
        ['type' => 'TD', 'content' => 'Bar'],
        ['type' => 'QUOTE', 'content' => 'A blockquote'],
        ['type' => 'QUOTE', 'content' => "Another type of blockquote\n\n> And another line"],
        ['type' => 'P', 'content' => 'This is a note'],
        ['type' => 'P', 'content' => '[Link](https://example.com)'],
        ['type' => 'P', 'content' => 'Ordered List'],
        ['type' => 'P', 'content' => 'With multiple lines'],
    ];


    $extractor = app()->make(Extractor::class);
    $extractor->useDefaultNodeExtractors();

    $units = $extractor->extract($markdown);

    foreach ($expected as $i => $expectedUnit) {
        expect($units[$i])->toBeInstanceOf(TranslatableUnit::class)
            ->and($units[$i]->type)->toBe($expectedUnit['type'])
            ->and($units[$i]->content)->toBe($expectedUnit['content']);
    }
});


