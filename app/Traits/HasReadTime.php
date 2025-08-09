<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasReadTime
{
    public function estimatedReadTime(): Attribute
    {
        return Attribute::get(function () {
            if (empty($this->content) || ! is_array($this->content)) {
                return null;
            }
            $combinedText = '';
            foreach ($this->content as $block) {
                if (! isset($block['type'], $block['data']['content'])) {
                    continue;
                }
                $content = $block['data']['content'];

                switch ($block['type']) {
                    case 'markdown':
                    case 'prism':
                        // Keep Markdown and code content as-is
                        $combinedText .= ' '.$content;
                        break;
                    case 'rich-editor':
                        // Strip HTML tags for rich text content
                        $combinedText .= ' '.strip_tags($content);
                        break;
                    default:
                        break;
                }
            }

            $wpm = 200;
            $wordCount = str_word_count($combinedText);

            return (int) ceil($wordCount / $wpm);
        });
    }
}
