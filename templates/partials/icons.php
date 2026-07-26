<?php
/**
 * Çizgi stil ikon seti (stroke: currentColor).
 */
function icon_svg(string $name): string
{
    $icons = [
        'construction' => '<path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4M9 12h.01M12 12h.01M15 12h.01M9 9h.01M12 9h.01M15 9h.01"/>',
        'automotive'   => '<path d="M5 17h-2v-5l2-5h12l4 5v5h-2M7 17a2 2 0 1 0 4 0 2 2 0 0 0-4 0Zm8 0a2 2 0 1 0 4 0 2 2 0 0 0-4 0ZM3 12h18"/>',
        'machinery'    => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.01a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.01a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/>',
        'agriculture'  => '<path d="M12 22V8M12 8c0-3.5 2.5-6 6-6 0 3.5-2.5 6-6 6Zm0 0C12 4.5 9.5 2 6 2c0 3.5 2.5 6 6 6Zm0 7c0-3 2-5 5-5 0 3-2 5-5 5Zm0 0c0-3-2-5-5-5 0 3 2 5 5 5Z"/>',
        'furniture'    => '<path d="M5 11V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4M4 21v-2M20 21v-2M3 15a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4H3v-4Z"/>',
        'industry'     => '<path d="M2 21h20M4 21V10l6 4v-4l6 4v-4l4 3v8M17 7l1-4h2l1 4"/>',
        'automation'   => '<path d="M8 21h8M12 21v-4M6 3h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm3 6h.01M15 9h.01M9 12h6"/>',
        'digital'      => '<rect x="7" y="7" width="10" height="10" rx="1"/><path d="M4 10h3M4 14h3M17 10h3M17 14h3M10 4v3M14 4v3M10 17v3M14 17v3"/>',
        'precision'    => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/><path d="M12 3v3M12 18v3M3 12h3M18 12h3"/>',
        'energy'       => '<path d="M13 2 4 14h6l-1 8 9-12h-6l1-8Z"/>',
        'leaf'         => '<path d="M6 21c0-9 4-15 14-17-1 10-6 15-13 15M4 21c2-4 5-7 9-9"/>',
        'globe'        => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 4 5.7 4 9s-1.5 6.4-4 9c-2.5-2.6-4-5.7-4-9s1.5-6.4 4-9Z"/>',
        'phone'        => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.13.97.36 1.9.7 2.8a2 2 0 0 1-.45 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.25a2 2 0 0 1 2.1-.45c.9.34 1.83.57 2.8.7a2 2 0 0 1 1.7 2Z"/>',
        'mail'         => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6L22 7"/>',
        'pin'          => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'clock'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'arrow'        => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'whatsapp'     => '<path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.7-1.2A9 9 0 1 0 12 3Z"/><path d="M9 8.5c0 4 2.5 6.5 6.5 6.5.4 0 .9-.4.9-.9v-1.3l-2.2-.7-.9.9c-1.1-.5-2-1.4-2.5-2.5l.9-.9-.7-2.2H9.9c-.5 0-.9.5-.9 1.1Z"/>',
    ];
    $path = $icons[$name] ?? $icons['industry'];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}

/** Marka logosu (inline SVG işaret). */
function brand_mark(): string
{
    return '<svg viewBox="0 0 44 44" fill="none" aria-hidden="true">'
        . '<rect x="1.5" y="1.5" width="41" height="41" rx="6" stroke="#f05a22" stroke-width="3"/>'
        . '<path d="M22 8 14 24h6l-2 12 10-17h-6l2-11Z" fill="#f05a22"/>'
        . '</svg>';
}
