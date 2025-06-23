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
        if (!($node instanceof TableSection)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . \get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $tagName = $node->getType() === TableSection::TYPE_HEAD ? 'thead' : 'tbody';

        if ($tagName === 'thead') {
            $attrs['class'] = 'text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400';
        }
        // No specific classes for tbody itself from the example

        return new HtmlElement($tagName, $attrs, $childRenderer->renderNodes($node->children()));
    }
}
