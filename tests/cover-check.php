<?php

// Run with: php tests/cover-check.php. No WordPress bootstrap or database is used.
function add_action(...$arguments): void
{
}

function add_filter(...$arguments): void
{
}

function esc_url(string $url): string
{
    return $url;
}

function esc_attr(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function get_theme_file_uri(string $path): string
{
    return 'https://example.test/theme/'.$path;
}

function get_the_post_thumbnail(int $post_id, string $size, array $attributes): string
{
    if (2 === $post_id) {
        return '';
    }

    $html = '<img src="https://example.test/featured.png" width="1200" height="630" alt="Custom cover"';

    foreach ($attributes as $name => $value) {
        if (false !== $value) {
            $html .= ' '.esc_attr($name).'="'.esc_attr($value).'"';
        }
    }

    return $html.'>';
}

require __DIR__.'/../web/app/themes/ymir/functions.php';

if ('--render' === ($argv[1] ?? '')) {
    echo ymir_blog_cover((int) ($argv[2] ?? 1));
    exit;
}

function expect(bool $condition, string $message): void
{
    if (!$condition) {
        fwrite(STDERR, 'FAIL: '.$message."\n");
        exit(1);
    }
}

foreach ([1, 2] as $post_id) {
    $document = new DOMDocument();
    $document->loadHTML(ymir_blog_cover($post_id), LIBXML_NOERROR | LIBXML_NOWARNING);
    $xpath = new DOMXPath($document);
    $active_images = $xpath->query('//img[not(ancestor::template)]');
    expect(1 === $active_images->length, 'PHP must render exactly one active cover image.');
    $image = $active_images->item(0);
    expect(YMIR_BLOG_COVER_SIZES === $image->getAttribute('sizes'), 'Responsive cover sizes must be retained.');

    if (1 === $post_id) {
        expect('https://example.test/featured.png' === $image->getAttribute('src'), 'The custom image must be the initial image.');
        expect('high' === $image->getAttribute('fetchpriority'), 'The custom image must retain high priority.');
        expect(!$image->hasAttribute('loading'), 'The custom image must not be lazy loaded.');
        expect('Custom cover' === $image->getAttribute('alt'), 'The custom alt text must be retained.');
        expect(1 === $xpath->query('//template/img')->length, 'A failed custom image needs an inert fallback.');
    } else {
        expect(str_ends_with($image->getAttribute('src'), '/blog-cover-technical.webp'), 'Posts without a thumbnail must render the default.');
        expect('' === $image->getAttribute('alt'), 'The generic default remains decorative.');
        expect(0 === $xpath->query('//template')->length, 'A default-only cover needs no deferred fallback.');
    }
}

fwrite(STDOUT, "PASS: custom/default initial PHP selection, inert failure fallback, responsive sizes, alt and loading priority.\n");
