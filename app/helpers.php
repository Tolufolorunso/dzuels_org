<?php

use App\Support\CmsTextFormatter;
use Illuminate\Support\HtmlString;

if (! function_exists('format_cms_text')) {
    /**
     * Format a CMS text string by escaping raw HTML, parsing inline styling tags,
     * and preserving line breaks.
     */
    function format_cms_text(?string $text, bool $preserveNewlines = true): HtmlString
    {
        return CmsTextFormatter::format($text, $preserveNewlines);
    }
}
