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
        if (!($node instanceof TableCell)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . \get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $tagName = $node->getType(); // 'th' or 'td'

        if ($tagName === TableCell::TYPE_HEADER) {
            $attrs['scope'] = 'col';
            $attrs['class'] = 'px-6 py-3';

            // Check if this th is a 'row' scope header cell in tbody (first cell in a body row)
            $parentRow = $node->parent();
            if ($parentRow && $parentRow->parent() instanceof \League\CommonMark\Extension\Table\TableSection && $parentRow->parent()->getType() === \League\CommonMark\Extension\Table\TableSection::TYPE_BODY) {
                 // This applies if the first cell of a body row is explicitly a header cell (e.g. `| Header | Data |` in CommonMark GFM)
                 // However, the example HTML has `th` as the first element in `tbody > tr`
                 // CommonMark GFM tables usually put `td` in `tbody` unless specified.
                 // For the example's structure `tbody > tr > th[scope=row]`, the AST might directly have a TableCell::TYPE_HEADER in a TableRow within a TYPE_BODY TableSection.
                 // Let's assume for now that if it's a TableCell::TYPE_HEADER and its parent row is in TYPE_BODY, it's the "scope=row" case.
                if ($parentRow->firstChild() === $node) { // Check if it's the first cell
                    $attrs['scope'] = 'row';
                    $attrs['class'] = 'px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white';
                }
            }
        } else { // TableCell::TYPE_CELL ('td')
            $isFirstCellInBodyRow = false;
            $parentRow = $node->parent();
            if ($parentRow instanceof \League\CommonMark\Extension\Table\TableRow &&
                $parentRow->parent() instanceof \League\CommonMark\Extension\Table\TableSection &&
                $parentRow->parent()->getType() === \League\CommonMark\Extension\Table\TableSection::TYPE_BODY &&
                $parentRow->firstChild() === $node) {
                $isFirstCellInBodyRow = true;
            }

            if ($isFirstCellInBodyRow) {
                $tagName = 'th'; // Change tag to <th>
                $attrs['scope'] = 'row';
                $attrs['class'] = 'px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white';
            } else {
                $attrs['class'] = 'px-6 py-4';
            }
        }

        return new HtmlElement($tagName, $attrs, $childRenderer->renderNodes($node->children()));
    }
}
