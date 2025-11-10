<?php

if (!function_exists('storage_asset_url')) {
    /**
     * Build a public asset URL for files stored directly under public/storage.
     *
     * @param  string|null  $value      Stored filename or relative path.
     * @param  string       $directory  Subdirectory under public/storage to use when only a filename is provided.
     * @return string|null
     */
    function storage_asset_url(?string $value, string $directory = ''): ?string
    {
        if (!$value) {
            return null;
        }

        $normalized = ltrim($value, '/');

        if (str_contains($normalized, '/')) {
            return asset('storage/' . $normalized);
        }

        $prefix = $directory !== '' ? trim($directory, '/') . '/' : '';

        return asset('storage/' . $prefix . $normalized);
    }
}

if (!function_exists('autoLinkUrls')) {
    /**
     * Convert plain URLs in text to clickable HTML links.
     * Safe by default; does not escape the rest of the content.
     * Callers should escape text before/after as needed.
     */
    function autoLinkUrls(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $pattern = '~(?:(https?)://|www\.)[^\s<]+~iu';

        $callback = static function ($matches) {
            $url = $matches[0];
            if (!str_starts_with(strtolower($url), 'http')) {
                $url = 'http://' . $url;
            }
            $display = $matches[0];
            return '<a href="' . e($url) . '" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">' . e($display) . '</a>';
        };

        return preg_replace_callback($pattern, $callback, $text) ?? $text;
    }
}


