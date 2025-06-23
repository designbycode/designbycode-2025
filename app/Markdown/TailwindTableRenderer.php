<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class TailwindTableRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof Table)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . \get_class($node));
        }

        $attrs = $node->data->get('attributes', []);
        $attrs['class'] = 'w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400';

        $tableElement = new HtmlElement('table', $attrs, $childRenderer->renderNodes($node->children()));

        $wrapperDivAttrs = [
            'class' => 'relative overflow-x-auto shadow-md sm:rounded-lg',
        ];
        return new HtmlElement('div', $wrapperDivAttrs, (string) $tableElement);
    }
}
