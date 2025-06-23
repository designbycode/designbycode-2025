<?php

declare(strict_types=1);

namespace App\Markdown;

use InvalidArgumentException;
use League\CommonMark\Extension\Table\TableRow;
use League\CommonMark\Extension\Table\TableSection;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use function get_class;

class TailwindTableRowRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof TableRow)) {
            throw new InvalidArgumentException('Incompatible node type: ' . get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $parent = $node->parent();

        if ($parent instanceof TableSection && $parent->getType() === TableSection::TYPE_BODY) {
            // Apply consistent border style to all <tr> in <tbody>
            // The user's example HTML:
            // <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
            // Let's use: "bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700"
            // The last row in their example did not have border-b: <tr class="bg-white dark:bg-gray-800">
            // CommonMark doesn't easily distinguish the last row without more complex logic or custom AST nodes.
            // So, a consistent style for all body rows is more straightforward here.
            $currentClasses = $attrs['class'] ?? '';
            $newClasses = 'bg-white border-b border-gray-200 dark:bg-gray-800 even:dark:bg-white/5 dark:border-gray-700';
            $attrs['class'] = trim($currentClasses . ' ' . $newClasses);

        } elseif ($parent instanceof TableSection && $parent->getType() === TableSection::TYPE_HEAD) {
            // No specific classes for <tr> in <thead> from the example, so do nothing here.
            // $attrs['class'] = ($attrs['class'] ?? '') . ' a-thead-tr-class';
        }

        return new HtmlElement('tr', $attrs, $childRenderer->renderNodes($node->children()));
    }
}
