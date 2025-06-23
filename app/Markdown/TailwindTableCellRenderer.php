<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Extension\Table\TableCell;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class TailwindTableCellRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof \League\CommonMark\Extension\Table\TableCell)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . \get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $nodeType = $node->getType(); // This is from League\CommonMark\Extension\Table\TableCell constants ('th' or 'td')

        $htmlTagName = '';

        if ($nodeType === \League\CommonMark\Extension\Table\TableCell::TYPE_HEADER) {
            $htmlTagName = 'th';
            $attrs['scope'] = 'col'; // Default scope for header cells
            $attrs['class'] = 'px-6 py-3'; // Default classes for <thead> <th>

            // It's unusual for a TableCell::TYPE_HEADER to be in a tbody in GFM,
            // but if it were, this renderer would style it like a thead th.
            // The logic to make a <td> into a <th> for row headers is in the TYPE_CELL block.
        } elseif ($nodeType === \League\CommonMark\Extension\Table\TableCell::TYPE_CELL) {
            $htmlTagName = 'td'; // Default tag for data cells
            $attrs['class'] = 'px-6 py-4'; // Default classes for <td>

            // Check if this <td> is the first cell in a <tbody> row
            $parentRow = $node->parent();
            if ($parentRow instanceof \League\CommonMark\Extension\Table\TableRow &&
                $parentRow->parent() instanceof \League\CommonMark\Extension\Table\TableSection &&
                $parentRow->parent()->getType() === \League\CommonMark\Extension\Table\TableSection::TYPE_BODY &&
                $parentRow->firstChild() === $node) {

                // If it's the first cell in a body row, change tag to <th> and apply special styling
                $htmlTagName = 'th';
                $attrs['scope'] = 'row';
                $attrs['class'] = 'px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white';
            }
        } else {
            // Should not happen with valid TableCell nodes
            throw new \RuntimeException("Invalid TableCell type: {$nodeType}");
        }

        return new HtmlElement($htmlTagName, $attrs, $childRenderer->renderNodes($node->children()));
    }
}
