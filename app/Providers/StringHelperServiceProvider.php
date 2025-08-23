<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class StringHelperServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Str::macro('cleanAiText', function ($text) {
            // Remove invisible characters
            $invisibleChars = [
                "\u{200B}", // Zero-width space
                "\u{200C}", // Zero-width non-joiner
                "\u{200D}", // Zero-width joiner
                "\u{FEFF}", // Zero-width no-break space - BOM
                "\u{00AD}", // Soft hyphen
                "\x{202A}-\x{202E}", // Directional formatting marks
            ];
            $text = str_replace($invisibleChars, '', $text);

            // Normalize or remove various types of dashes
            $dashes = [
                "\u{2013}", // En dash
                "\u{2014}", // Em dash
                "\u{2012}", // Figure dash
                "\u{2011}", // Non-breaking hyphen
                "\u{0097}", // ASCII EM DASH substitute
                "\u{0096}", // ASCII EN DASH substitute
            ];
            $text = str_replace($dashes, '-', $text); // replace with '' to remove completely

            // Normalize whitespace
            $text = preg_replace('/\s+/', ' ', $text);

            return trim($text);
        });
    }
}
