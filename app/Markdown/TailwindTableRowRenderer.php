<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Extension\Table\TableRow;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class TailwindTableRowRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof \League\CommonMark\Extension\Table\TableRow)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . \get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $parent = $node->parent();

        if ($parent instanceof \League\CommonMark\Extension\Table\TableSection && $parent->getType() === \League\CommonMark\Extension\Table\TableSection::TYPE_BODY) {
            $currentClasses = $attrs['class'] ?? '';
            $configuredClasses = config('tailwind_tables.tbody_row', 'bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700');
            if (!empty($configuredClasses)) {
                $attrs['class'] = trim($currentClasses . ' ' . $configuredClasses);
            } elseif (empty($currentClasses) && !isset($attrs['class'])) {
                // Ensure 'class' attribute is not present if no classes are defined
                // HtmlElement handles this, so this might be overly cautious.
            }
        } elseif ($parent instanceof \League\CommonMark\Extension\Table\TableSection && $parent->getType() === \League\CommonMark\Extension\Table\TableSection::TYPE_HEAD) {
            // No specific classes for <tr> in <thead> from the example or current config structure.
            // One could add a 'thead_row' key to config if needed.
            // $configuredClasses = config('tailwind_tables.thead_row', '');
            // if (!empty($configuredClasses)) { ... }
        }

        return new HtmlElement('tr', $attrs, $childRenderer->renderNodes($node->children()));
    }
}
