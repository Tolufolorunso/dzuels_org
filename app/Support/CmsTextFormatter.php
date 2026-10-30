<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class CmsTextFormatter
{
    /**
     * Map of supported BBCode-style tags to their HTML replacement templates.
     * All user text is HTML-escaped before tag expansion to prevent XSS.
     *
     * @var array<string, string>
     */
    protected static array $tagReplacements = [
        'accent' => '<span class="text-maroon dark:text-mint font-semibold">$1</span>',
        'crimson' => '<span class="text-crimson font-bold">$1</span>',
        'mint' => '<span class="text-mint font-bold">$1</span>',
        'cream' => '<span class="text-[var(--accent-cream)] font-bold">$1</span>',
        'maroon' => '<span class="text-maroon dark:text-crimson font-bold">$1</span>',
        'gold' => '<span class="text-amber-500 font-bold">$1</span>',
        'bold' => '<strong class="font-bold text-[var(--heading)]">$1</strong>',
        'italic' => '<em class="italic">$1</em>',
        'underline' => '<span class="underline decoration-crimson/50 underline-offset-4 font-semibold">$1</span>',
        'badge' => '<span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint border border-maroon/15 dark:border-mint/25">$1</span>',
    ];

    /**
     * Format a CMS text string by escaping raw HTML, parsing inline styling tags,
     * and preserving line breaks.
     */
    public static function format(?string $text, bool $preserveNewlines = true): HtmlString
    {
        if ($text === null || $text === '') {
            return new HtmlString('');
        }

        // 1. Strictly escape raw HTML to prevent XSS
        $sanitized = e($text);

        // 2. Parse inline styling tags
        foreach (self::$tagReplacements as $tag => $replacement) {
            $pattern = '/\['.preg_quote($tag, '/').'\](.*?)\[\/'.preg_quote($tag, '/').'\]/is';
            $sanitized = preg_replace($pattern, $replacement, $sanitized);
        }

        // 3. Preserve line breaks if requested
        if ($preserveNewlines) {
            $sanitized = nl2br($sanitized);
        }

        return new HtmlString($sanitized);
    }
}
