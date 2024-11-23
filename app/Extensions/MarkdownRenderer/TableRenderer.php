<?php

namespace App\Extensions\MarkdownRenderer;

use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Extension\Table\TableCell;
use League\CommonMark\Extension\Table\TableSection;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

final class TableRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        Table::assertInstanceOf($node);

        [$head, $body] = $node->children();

        TableSection::assertInstanceOf($head);
        TableSection::assertInstanceOf($body);

        $headings = [];
        $headingAlignments = [];
        $rows = [];

        foreach ($head->firstChild()->children() as $cell) {
            /** @var TableCell $cell */
            TableCell::assertInstanceOf($cell);
            $headings[] = $childRenderer->renderNodes($cell->children());
            $headingAlignments[] = $cell->getAlign();
        }
        ray($headingAlignments);

        foreach ($body->children() as $row) {
            $cells = [];
            foreach ($row->children() as $cell) {
                $cells[] = $childRenderer->renderNodes($cell->children());
            }
            $rows[] = $cells;
        }

        $output = '| ' . implode(' | ', $headings) . " |\n";
        $output .= '| ' . implode(
                ' | ',
                array_map(
                    fn ($alignment) => match ($alignment) {
                        TableCell::ALIGN_LEFT => ':---',
                        TableCell::ALIGN_CENTER => ':---:',
                        TableCell::ALIGN_RIGHT => '---:',
                        default => '---',
                    },
                    $headingAlignments,
                )
            ) . " |\n";
        foreach ($rows as $row) {
            $output .= '| ' . implode(' | ', $row) . " |\n";
        }

        return $output;
    }
}
