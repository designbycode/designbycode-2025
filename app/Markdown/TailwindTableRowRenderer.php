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
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        if (!($node instanceof TableRow)) {
            throw new InvalidArgumentException('Incompatible node type: ' . get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $parent = $node->parent();

        if ($parent instanceof TableSection && $parent->getType() === TableSection::TYPE_BODY) {
            $currentClasses = $attrs['class'] ?? '';
            $configuredClasses = config('tailwind_tables.tbody_row', '');
            if (!empty($configuredClasses)) {
                $attrs['class'] = trim($currentClasses . ' ' . $configuredClasses);
            } elseif (empty($currentClasses) && !isset($attrs['class'])) {
                // Ensure 'class' attribute is not present if no classes are defined
                // HtmlElement handles this, so this might be overly cautious.
            }

        } elseif ($parent instanceof TableSection && $parent->getType() === TableSection::TYPE_HEAD) {

            // No specific classes for <tr> in <thead> from the example or current config structure.
            // One could add a 'thead_row' key to config if needed.
            // $configuredClasses = config('tailwind_tables.thead_row', '');
            // if (!empty($configuredClasses)) { ... }
        }

        return new HtmlElement('tr', $attrs, $childRenderer->renderNodes($node->children()));
    }
}
