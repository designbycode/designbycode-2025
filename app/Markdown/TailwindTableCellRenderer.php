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
        // getType() directly returns 'th' or 'td'
        $cellTypeString = $node->getType();

        $htmlTagName = $cellTypeString; // Use 'th' or 'td' directly as the tag name initially

        if ($htmlTagName === 'th') {
            $attrs['scope'] = 'col'; // Default scope for header cells
            $attrs['class'] = 'px-6 py-3'; // Default classes for <thead> <th>

            // If a 'th' is found in the body (less common for GFM, but possible if AST is built that way)
            // and it's the first cell, it might need the special row header styling.
            // However, the primary logic for styling the first body cell as a 'th' is below for 'td' types.
            // This block primarily styles 'th' elements typically found in 'thead'.
            $parentRow = $node->parent();
            if ($parentRow instanceof \League\CommonMark\Extension\Table\TableRow &&
                $parentRow->parent() instanceof \League\CommonMark\Extension\Table\TableSection &&
                $parentRow->parent()->getType() === \League\CommonMark\Extension\Table\TableSection::TYPE_BODY &&
                $parentRow->firstChild() === $node) {
                  // This is a 'th' that is also the first cell in a body row. Apply special styling.
                  $attrs['scope'] = 'row';
                  $attrs['class'] = 'px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white';
            }

        } elseif ($htmlTagName === 'td') {
            $attrs['class'] = 'px-6 py-4'; // Default classes for <td>

            // Check if this <td> is the first cell in a <tbody> row
            $parentRow = $node->parent();
            if ($parentRow instanceof \League\CommonMark\Extension\Table\TableRow &&
                $parentRow->parent() instanceof \League\CommonMark\Extension\Table\TableSection &&
                $parentRow->parent()->getType() === \League\CommonMark\Extension\Table\TableSection::TYPE_BODY &&
                $parentRow->firstChild() === $node) {

                // If it's the first <td> in a body row, change tag to <th> and apply special styling
                $htmlTagName = 'th'; // Override tag to 'th'
                $attrs['scope'] = 'row';
                $attrs['class'] = 'px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white';
            }
        } else {
            // Should not happen with valid TableCell nodes from GFM TableExtension
            throw new \RuntimeException("Invalid TableCell type string: {$cellTypeString}");
        }

        return new HtmlElement($htmlTagName, $attrs, $childRenderer->renderNodes($node->children()));
    }
}
