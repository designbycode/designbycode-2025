<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Extension\Table\TableCell;
use League\CommonMark\Extension\Table\TableRow;
use League\CommonMark\Extension\Table\TableSection;
use League\CommonMark\Extension\Table\TableExtension as CoreTableExtension; // To ensure table parsing is enabled

class TailwindTableExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        // Ensure the core TableExtension is registered so table parsing is enabled
        // This might be redundant if CommonMarkCoreExtension or another extension already adds it,
        // but it's safer to ensure it's present.
        // However, graham-campbell/markdown already registers TableExtension by default if it's in the config.
        // We will be replacing TableExtension in the config with this one.
        // So, this extension needs to ensure the parsers from TableExtension are added.
        // A simpler way: this extension can just add renderers, assuming TableExtension's parsers are already active.
        // The EnvironmentBuilder allows adding multiple renderers for the same block type; the last one added usually wins.

        // Let's assume TableExtension's parsers are added by its presence in config/markdown.php's 'extensions' array initially.
        // We will replace it with this extension. So, this extension *must* also add the GFM table parsing capabilities.
        // The easiest way to do this is to also register the CoreTableExtension from within this extension.
        $environment->addExtension(new CoreTableExtension());

        // Add our custom renderers
        $environment->addRenderer(Table::class, new TailwindTableRenderer(), 10);
        $environment->addRenderer(TableSection::class, new TailwindTableSectionRenderer(), 10);
        $environment->addRenderer(TableRow::class, new TailwindTableRowRenderer(), 10);
        $environment->addRenderer(TableCell::class, new TailwindTableCellRenderer(), 10);
    }
}
