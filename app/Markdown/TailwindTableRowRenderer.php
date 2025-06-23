<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Extension\Table\TableRow;
use League\CommonMark\Extension\Table\TableSection;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class TailwindTableRowRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof TableRow)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . \get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $parent = $node->parent();

        if ($parent instanceof TableSection && $parent->getType() === TableSection::TYPE_BODY) {
            // Default classes for <tr> in <tbody>
            $attrs['class'] = 'bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700';
            // Example: "bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200"
            // Simplified to: "bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700"
            // The border-gray-200 is for light mode bottom border.
        }
        // No specific classes for <tr> in <thead> from the example

        return new HtmlElement('tr', $attrs, $childRenderer->renderNodes($node->children()));
    }
}
