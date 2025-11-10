<?php

// Keep backward compatibility for existing calls to storage_url()
if (!function_exists('storage_url')) {
    /**
     * Build a public asset URL for files stored under public/storage.
     *
     * Accepts either a bare filename (e.g. "image.jpg") or a relative path
     * like "posters/image.jpg" and returns a full asset URL.
     */
    function storage_url(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $normalized = ltrim($value, '/');

        // If value already includes a directory, use as-is; otherwise assume posters/
        $relative = str_contains($normalized, '/')
            ? $normalized
            : ('posters/' . $normalized);

        return asset('storage/' . $relative);
    }
}


