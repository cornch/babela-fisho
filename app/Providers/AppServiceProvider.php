<?php

namespace App\Providers;

use App\Extensions\Block\Element\Callout;
use App\Extensions\MarkdownRenderer\CalloutRenderer;
use App\Extensions\MarkdownRenderer\StrikethroughRenderer;
use App\Extensions\Parsers\CalloutParser;
use App\Markdown\Parsers\MarkdownDivParser;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Strikethrough\Strikethrough;
use League\CommonMark\Parser\MarkdownParser;
use League\CommonMark\Parser\MarkdownParserInterface;
use Wnx\CommonmarkMarkdownRenderer\MarkdownRendererExtension;
use Wnx\CommonmarkMarkdownRenderer\Renderer\MarkdownRenderer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(
            MarkdownRenderer::class,
            function () {
                $environment = new Environment();
                $environment->addExtension(new MarkdownRendererExtension());
                $environment->addRenderer(Strikethrough::class, new StrikethroughRenderer());
                $environment->addRenderer(Callout::class, new CalloutRenderer());

                return new MarkdownRenderer($environment);
            },
        );

        $this->app->singleton(
            MarkdownParserInterface::class,
            function () {
                $environment = new Environment();
                $environment->addBlockStartParser(CalloutParser::createBlockStartParser(), 80);
                $environment->addExtension(new CommonMarkCoreExtension());
                $environment->addExtension(new GithubFlavoredMarkdownExtension());

                return new MarkdownParser($environment);
            },
        );
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {

    }
}
