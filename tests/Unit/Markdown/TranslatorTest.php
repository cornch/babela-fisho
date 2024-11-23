<?php

use App\Markdown\Translator;
use Gettext\Translation;
use Gettext\Translations;
use Wnx\CommonmarkMarkdownRenderer\Renderer\MarkdownRenderer;

it('translates a markdown', function () {
    $markdown = <<<'MARKDOWN'
    # Hello World

    This is a paragraph.

    ```php
    // This is a code block
    echo 'Hello World';
    ```
    
    > [!NOTE]  
    > This is a note callout.

    ## Another heading

    - List item 1
    - List item 2
      - Sub-list item 1

    Another paragraph. Keep untranslated.

    | Header 1 | Header 2 | Header 3 | Header 4 |
    |---------|--------: | :----------: |----------|
    | Row 1    | Row 2    | Row 3    | Row 4    |
    MARKDOWN;

    $po = Translations::create(null, 'eo');
    $translationsText = [
        'Hello World' => 'Saluton Mondo',
        'This is a paragraph.' => 'Tio estas paragrafo.',
        "// This is a code block\necho 'Hello World';" => "// Tio estas koda bloko\necho 'Saluton Mondo';",
        'This is a note callout.' => 'Tio estas nota callout.',
        'Another heading' => 'Alia titolo',
        'List item 1' => 'Listo elemento 1',
        'List item 2' => 'Listo elemento 2',
        'Sub-list item 1' => 'Sublisto elemento 1',
        'Header 1' => 'Kapo 1',
        'Header 2' => 'Kapo 2',
        'Header 3' => 'Kapo 3',
        'Header 4' => 'Kapo 4',
        'Row 1' => 'Vico 1',
        'Row 2' => 'Vico 2',
        'Row 3' => 'Vico 3',
        'Row 4' => 'Vico 4',
    ];

    foreach ($translationsText as $original => $translation) {
        $po->add(Translation::create(null, $original)->translate($translation));
    }

    $translator = app()->make(Translator::class);
    $document = $translator->translate($markdown, $po);

    $renderer = app()->make(MarkdownRenderer::class);

    expect((string)$renderer->renderDocument($document))
        ->toBe(
            <<<'MARKDOWN'
            # Saluton Mondo

            Tio estas paragrafo.

            ```php
            // Tio estas koda bloko
            echo 'Saluton Mondo';
            ```
            > [!NOTE]  
            > Tio estas nota callout.

            ## Alia titolo

            - Listo elemento 1
            - Listo elemento 2
              - Sublisto elemento 1
              

            Another paragraph. Keep untranslated.

            | Kapo 1 | Kapo 2 | Kapo 3 | Kapo 4 |
            | --- | ---: | :---: | --- |
            | Vico 1 | Vico 2 | Vico 3 | Vico 4 |

            MARKDOWN,
        );
});


