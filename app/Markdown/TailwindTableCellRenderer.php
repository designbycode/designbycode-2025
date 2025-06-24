<?php

declare(strict_types=1);

namespace App\Markdown;

use InvalidArgumentException;
use League\CommonMark\Extension\Table\TableCell;
use League\CommonMark\Extension\Table\TableRow;
use League\CommonMark\Extension\Table\TableSection;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use RuntimeException;
use function get_class;

class TailwindTableCellRenderer implements NodeRendererInterface
{
    /**
     * @param Node $node
     * @param ChildNodeRendererInterface $childRenderer
     * @return HtmlElement
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        if (!($node instanceof TableCell)) {
            throw new InvalidArgumentException('Incompatible node type: ' . get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $cellTypeString = strtolower($node->getType()); // Normalize to lowercase, e.g., "header" or "th"

        $htmlTagName = '';

        // Determine the intended HTML tag ('th' or 'td') and apply base styling
        if ($cellTypeString === 'th' || $cellTypeString === 'header') {
            $htmlTagName = 'th';
            $attrs['scope'] = 'col';
            $attrs['class'] = config('tailwind_tables.th_head', 'px-6 py-3');
        } elseif ($cellTypeString === 'td' || $cellTypeString === 'cell' || $cellTypeString === 'data') {
            $htmlTagName = 'td';
            $attrs['class'] = config('tailwind_tables.td_cell', 'px-6 py-4');
        } else {
            throw new RuntimeException("Unknown TableCell type string: {$cellTypeString}");
        }

        // Specific styling adjustments based on context (e.g., first cell in tbody row)
        $parentRow = $node->parent();
        if ($parentRow instanceof TableRow &&
            $parentRow->parent() instanceof TableSection &&
            $parentRow->parent()->getType() === TableSection::TYPE_BODY &&
            $parentRow->firstChild() === $node) {

            // This is the first cell in a body row. Style it as a row header.
            $htmlTagName = 'th'; // Ensure it's a <th>
            $attrs['scope'] = 'row';
            $attrs['class'] = config('tailwind_tables.th_body_row_header', 'px-6 py-4  whitespace-nowrap ');
        }
        // Note: The original 'elseif ($htmlTagName === 'th')' block is implicitly handled.
        // If it's a 'th' (from cellTypeString) and NOT the first cell in a body row,
        // it will retain the $htmlTagName = 'th' and the classes/scope set initially from 'th_head'.
        // This seems correct as per current requirements.

        return new HtmlElement($htmlTagName, $attrs, $childRenderer->renderNodes($node->children()));
    }
}
