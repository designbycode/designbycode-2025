<?php

namespace App\Markdown;

use App\View\Components\Block\Prism;
use Exception;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;


class CustomFencedCodeExtension implements ExtensionInterface, NodeRendererInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 10);

    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        FencedCode::assertInstanceOf($node);

        try {
            $attrs = $node->data->get('attributes', []);
            $infoWords = $node->getInfoWords();


            // Extract language from info string with extra safety
            $language = '';
            if (is_array($infoWords) && count($infoWords) > 0) {
                $language = $infoWords[0] ?? '';
            }


            // Get the code content
            $code = $node->getLiteral() ?? '';

            // Use your existing Prism component
            $component = new Prism(
                language: $language ?: 'text',
                code: $code,
                showCopy: true
            );

            return view('components.block.prism', [
                'language' => $language ?: 'text',
                'code' => $code,
                'showCopy' => true,
//                'slot' => $code // Also pass as slot for fallback
            ])->render();

        } catch (Exception $e) {
            // Enhanced error logging

            // Fallback to default rendering if component fails
            return $this->renderFallback($code ?? '', $language ?? '', $attrs ?? []);

        }
    }

    private function renderFallback(string $code, string $language, array $attrs = []): string
    {
        $attributes = [];

        if (!empty($language)) {
            $attributes['class'] = 'language-' . $language;
        }

        // Merge any additional attributes
        $attributes = array_merge($attributes, $attrs);

        return new HtmlElement(
            'pre',
            [],
            new HtmlElement('code', $attributes, htmlspecialchars($code))
        );
    }

}
