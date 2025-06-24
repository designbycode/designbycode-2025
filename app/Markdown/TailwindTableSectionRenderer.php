<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Extension\Table\TableSection;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class TailwindTableSectionRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof \League\CommonMark\Extension\Table\TableSection)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . \get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $tagName = $node->getType() === \League\CommonMark\Extension\Table\TableSection::TYPE_HEAD ? 'thead' : 'tbody';

        $currentClasses = $attrs['class'] ?? '';
        $newClasses = '';

        if ($tagName === 'thead') {
            $newClasses = config('tailwind_tables.thead', 'text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400');
        } else { // tbody
            // No specific classes for <tbody> itself from the example or config by default
            // $newClasses = config('tailwind_tables.tbody', ''); // Example if you add a 'tbody' key to config
        }

        if (!empty($newClasses)) {
            $attrs['class'] = trim($currentClasses . ' ' . $newClasses);
        } elseif (empty($currentClasses) && !isset($attrs['class'])) { // Check if class was not set at all
             // If $newClasses is empty AND $currentClasses was empty AND $attrs['class'] was not touched by $node->data,
             // ensure 'class' attribute is not present if no classes are defined from config or node data.
             // This might be redundant if $attrs['class'] is only set if there are classes.
             // The primary goal is that if config returns empty and node had no class, no 'class=""' is output.
             // HtmlElement usually handles empty class attributes correctly (doesn't render them).
        }


        return new HtmlElement($tagName, $attrs, $childRenderer->renderNodes($node->children()));
    }
}
