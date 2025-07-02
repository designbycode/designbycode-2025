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

        $tableConfiguredClass = config('tailwind_tables.table', 'w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400');

        $tableAttributes['class'] = trim(($tableAttributes['class'] ?? '') . ' ' . $tableConfiguredClass);

        // Render inner content of the table (thead, tbody)
        $innerTableContent = $childRenderer->renderNodes($node->children());
        $tableElement = new HtmlElement('table', $tableAttributes, $innerTableContent);

        // Attributes for the wrapping <div>
        $wrapperDivAttributes = [
            'class' => config('tailwind_tables.wrapper', 'relative overflow-x-auto shadow-md sm:rounded-lg'),
        ];

        return new HtmlElement('div', $wrapperDivAttributes, (string)$tableElement);
    }
}
