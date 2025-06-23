<?php

declare(strict_types=1);

namespace App\Markdown;

use InvalidArgumentException;
use League\CommonMark\Extension\Table\TableSection;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use function get_class;

class TailwindTableSectionRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof TableSection)) {
            throw new InvalidArgumentException('Incompatible node type: ' . get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $tagName = $node->getType() === TableSection::TYPE_HEAD ? 'thead' : 'tbody';

        $currentClasses = $attrs['class'] ?? '';
        $newClasses = '';

        if ($tagName === 'thead') {
            $newClasses = 'text-xs text-gray-700 uppercase bg-black/10 dark:bg-white/10 dark:text-gray-400 ';
        } else { // tbody
            // No specific classes for <tbody> itself from the example
            // $newClasses = 'a-tbody-class';
        }

        if (!empty($newClasses)) {
            $attrs['class'] = trim($currentClasses . ' ' . $newClasses);
        } elseif (empty($currentClasses)) {
            // Ensure 'class' attribute is not present if no classes are defined
            unset($attrs['class']);
        }


        return new HtmlElement($tagName, $attrs, $childRenderer->renderNodes($node->children()));
    }
}
