<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\Table\Table; // AST Node
use League\CommonMark\Extension\Table\TableCell; // AST Node
use League\CommonMark\Extension\Table\TableExtension as CoreTableExtension; // AST Node
use League\CommonMark\Extension\Table\TableRow; // AST Node
use League\CommonMark\Extension\Table\TableSection; // The actual GFM Table parsing extension

class TailwindTableExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        // 1. Ensure the core GFM TableExtension's parsers are registered.
        // This is crucial because we are replacing TableExtension in the config.
        // If CommonMarkCoreExtension or another foundational extension already adds
        // table *parsing*, this might be redundant but harmless.
        // However, TableExtension specifically adds GFM table parsing.
        $environment->addExtension(new CoreTableExtension);

        // 2. Add our custom renderers with a priority.
        // The priority value (e.g., 10) ensures these renderers are chosen over
        // any default renderers that CoreTableExtension might register for the same AST nodes.
        $environment->addRenderer(Table::class, new TailwindTableRenderer, 10);
        $environment->addRenderer(TableSection::class, new TailwindTableSectionRenderer, 10);
        $environment->addRenderer(TableRow::class, new TailwindTableRowRenderer, 10);
        $environment->addRenderer(TableCell::class, new TailwindTableCellRenderer, 10);
    }
}
