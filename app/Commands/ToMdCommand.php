<?php

namespace App\Commands;

use App\Markdown\Extractor;
use App\Markdown\Translator;
use Gettext\Generator\PoGenerator;
use Gettext\Loader\PoLoader;
use Gettext\Translation;
use Gettext\Translations;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Wnx\CommonmarkMarkdownRenderer\Renderer\MarkdownRenderer;

final class ToMdCommand extends Command
{
    protected $signature = 'to-md {input-markdown} {input-po} {--output=}';
    protected $description = 'Convert markdown file(s) to po file';

    public function handle(
        Translator $translator,
        MarkdownRenderer $renderer,
    ): int
    {
        $inputMarkdown = $this->argument('input-markdown');
        $inputPo = $this->argument('input-po');
        $output = $this->option('output') ?: 'php://stdout';

        $po = new PoLoader()->loadFile($inputPo);

        $markdown = File::get($inputMarkdown);

        $document = $translator->translate(markdown: $markdown, translations: $po);

        $output = $renderer->renderDocument($document);

        echo $output;

        return self::SUCCESS;
    }
}
