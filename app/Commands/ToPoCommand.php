<?php

namespace App\Commands;

use App\Markdown\Extractor;
use App\Markdown\NodeExtractors\CodeExtractor;
use App\Markdown\NodeExtractors\HeadingExtractor;
use App\Markdown\NodeExtractors\HtmlExtractor;
use App\Markdown\NodeExtractors\ParagraphExtractor;
use App\Markdown\NodeExtractors\QuoteExtractor;
use App\Markdown\NodeExtractors\TableCellExtractor;
use Gettext\Generator\PoGenerator;
use Gettext\Translation;
use Gettext\Translations;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use function app;
use function array_map;

final class ToPoCommand extends Command
{
    protected $signature = 'to-po {inputs*} {--output=}';
    protected $description = 'Convert markdown file(s) to po file';

    public function handle(Extractor $extractor): int
    {
        $inputs = $this->argument('inputs');
        $output = $this->option('output') ?: 'php://stdout';

        $poGenerator = new PoGenerator();
        $pendingTranslations = [];
        $existingTranslations = [];

        foreach ($inputs as $input) {
            $markdown = File::get($input);
            $translatableUnits = $extractor->extract($markdown);

            foreach ($translatableUnits as $translatableUnit) {
                $content = $translatableUnit->content;
                $translation = $existingTranslations[$content]
                    ??= Translation::create($translatableUnit->type, $translatableUnit->content);
                $translation->getReferences()->add($input, $translatableUnit->line);

                $pendingTranslations[] = $translation;
            }
        }

        usort(
            $pendingTranslations,
            static function (Translation $a, Translation $b): int {
                $aArr = $a->getReferences()->toArray();
                $bArr = $b->getReferences()->toArray();

                $aFile = array_key_first($aArr);
                $bFile = array_key_first($bArr);

                $aLine = $aArr[$aFile][0] ?? 0;
                $bLine = $bArr[$bFile][0] ?? 0;

                if ($aLine === $bLine) {
                    return $aFile <=> $bFile;
                }

                return $aLine <=> $bLine;
            },
        );

        $translations = Translations::create();

        foreach ($pendingTranslations as $translation) {
            $translations->add($translation);
        }

        $poGenerator->generateFile($translations, $output);

        return self::SUCCESS;
    }
}
