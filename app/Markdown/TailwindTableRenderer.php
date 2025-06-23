<?php

declare(strict_types=1);

namespace App\Markdown;

use InvalidArgumentException;
use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use function get_class;

class TailwindTableRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        if (!($node instanceof Table)) {
            throw new InvalidArgumentException('Incompatible node type: ' . get_class($node));
        }

        // Attributes for the main <table> element
        $tableAttributes = $node->data->get('attributes', []);
        $tableAttributes['class'] = trim(($tableAttributes['class'] ?? '') . ' w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400');

        // Render inner content of the table (thead, tbody)
        $innerTableContent = $childRenderer->renderNodes($node->children());
        $tableElement = new HtmlElement('table', $tableAttributes, $innerTableContent);

        // Attributes for the wrapping <div>
        $wrapperDivAttributes = [
            'class' => 'relative border dark:border-white/5 border-black/5 overflow-x-auto shadow-md sm:rounded-lg prose-table:my-0',
        ];

        return new HtmlElement('div', $wrapperDivAttributes, (string)$tableElement);
    }
}
