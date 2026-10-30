<?php

/**
 * Topic categories shown in the collection navigation, keyed by category slug.
 */
const YMIR_BLOG_TOPICS = [
    'guide' => 'Guides',
    'technology' => 'Technology',
    'opinion' => 'Opinion',
];

/**
 * Articles per collection page, including the feature on the first page.
 */
const YMIR_BLOG_POSTS_PER_PAGE = 6;

/**
 * Main ymirapp.com site that owns the header and footer destinations.
 */
const YMIR_BLOG_MAIN_SITE_URL = 'https://ymirapp.com';

/**
 * Category of customer stories, which live on the main site instead of the editorial collection.
 */
const YMIR_BLOG_CASE_STUDY_CATEGORY = 'case-study';

/**
 * Rendered width of the feature cover: the full column when stacked, about 560px beside the title.
 */
const YMIR_BLOG_COVER_SIZES = '(max-width: 900px) 86vw, 560px';

/**
 * Custom field that opts a post into showing its publication date.
 */
const YMIR_BLOG_SHOW_DATE_META_KEY = 'showDate';

/**
 * Longest collection excerpt, in characters, before it's shortened at a word boundary.
 */
const YMIR_BLOG_EXCERPT_LENGTH = 230;

/**
 * Register the WordPress features the theme supports.
 */
function ymir_blog_setup(): void
{
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'navigation-widgets', 'script', 'search-form', 'style']);
}
add_action('after_setup_theme', 'ymir_blog_setup');

/**
 * Enqueue the theme styles and scripts.
 */
function ymir_blog_enqueue_assets(): void
{
    $version = wp_get_theme()->get('Version');

    wp_enqueue_style('ymir', get_stylesheet_uri(), [], $version);
    wp_enqueue_script('ymir-navigation', get_theme_file_uri('assets/js/navigation.js'), [], $version, true);

    if (is_home() && !is_paged()) {
        wp_enqueue_script('ymir-cover', get_theme_file_uri('assets/js/cover.js'), [], $version, true);
    }

    if (is_singular()) {
        wp_enqueue_style('ymir-highlight', get_theme_file_uri('assets/css/highlight.min.css'), ['ymir'], $version);
        wp_enqueue_script('ymir-highlight', get_theme_file_uri('assets/js/highlight.js'), [], $version, true);
        wp_add_inline_script('ymir-highlight', "document.querySelectorAll('.entry-content pre').forEach((block) => hljs.highlightBlock(block));");
        wp_enqueue_script('ymir-scroll-regions', get_theme_file_uri('assets/js/scroll-regions.js'), [], $version, true);
    }
}
add_action('wp_enqueue_scripts', 'ymir_blog_enqueue_assets');

/**
 * Preload the fonts used by the first viewport.
 */
function ymir_blog_preload_fonts(): void
{
    foreach (['geist', 'geist-mono'] as $font) {
        printf('<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>'."\n", esc_url(get_theme_file_uri("assets/fonts/{$font}.woff2")));
    }
}
add_action('wp_head', 'ymir_blog_preload_fonts', 1);

/**
 * Use the bundled Ymir icons for the native site icon sizes.
 *
 * Filtering the URL keeps the native icon markup and `/favicon.ico` redirect while leaving the stored
 * `site_icon` option untouched for other themes.
 */
function ymir_blog_site_icon_url(string $url, int $size): string
{
    if ($size <= 32) {
        return get_theme_file_uri('assets/images/favicon-32x32.png');
    }

    if ($size <= 180) {
        return get_theme_file_uri('assets/images/apple-touch-icon.png');
    }

    if ($size <= 192) {
        return get_theme_file_uri('assets/images/android-chrome-192x192.png');
    }

    return get_theme_file_uri('assets/images/android-chrome-512x512.png');
}
add_filter('get_site_icon_url', 'ymir_blog_site_icon_url', 10, 2);

/**
 * Show six editorial articles per collection page, without case studies.
 *
 * The blog index also ignores sticky posts so the feature is always the latest article and a page
 * never exceeds six articles.
 */
function ymir_blog_pre_get_posts(WP_Query $query): void
{
    if (is_admin() || !$query->is_main_query() || $query->is_feed() || (!$query->is_home() && !$query->is_category(array_keys(YMIR_BLOG_TOPICS)))) {
        return;
    }

    if ($query->is_home()) {
        $query->set('ignore_sticky_posts', true);
    }

    $query->set('category__not_in', array_merge(array_filter((array) $query->get('category__not_in')), ymir_blog_case_study_category_ids()));
    $query->set('posts_per_page', YMIR_BLOG_POSTS_PER_PAGE);
}
add_action('pre_get_posts', 'ymir_blog_pre_get_posts');

/**
 * Get the IDs of the case study category excluded from editorial collections.
 */
function ymir_blog_case_study_category_ids(): array
{
    $category = get_category_by_slug(YMIR_BLOG_CASE_STUDY_CATEGORY);

    return $category instanceof WP_Term ? [$category->term_id] : [];
}

/**
 * Get the collection excerpt of a post: the plain text of its opening paragraph, or of its native excerpt
 * when it has none, shortened to a word boundary with an ellipsis when it's too long.
 *
 * Renders the content the way WordPress does for automatic excerpts so stored content and excerpts stay untouched.
 */
function ymir_blog_excerpt(WP_Post $post): string
{
    $content = apply_filters('the_content', excerpt_remove_blocks(strip_shortcodes(get_the_content('', false, $post))));
    $html = preg_match('#<p\b#i', $content) ? $content : get_the_excerpt($post);
    $text = preg_match('#<p\b[^>]*>(.*?)</p>#is', $html, $paragraph) ? $paragraph[1] : $html;
    $text = trim((string) preg_replace('/(*UCP)\s+/u', ' ', html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

    if (mb_strlen($text) <= YMIR_BLOG_EXCERPT_LENGTH) {
        return $text;
    }

    $text = mb_substr($text, 0, YMIR_BLOG_EXCERPT_LENGTH);
    $space = mb_strrpos($text, ' ');

    return rtrim(false !== $space ? mb_substr($text, 0, $space) : $text, '.,;:').'…';
}

/**
 * Print the publication date of a post that opted in with the `showDate` custom field.
 */
function ymir_blog_publication_date(WP_Post $post): void
{
    if (!filter_var(get_post_meta($post->ID, YMIR_BLOG_SHOW_DATE_META_KEY, true), FILTER_VALIDATE_BOOLEAN)) {
        return;
    }

    printf('<time datetime="%s">%s</time>', esc_attr(get_the_date('Y-m-d', $post)), esc_html(get_the_date('F j, Y', $post)));
}

/**
 * Get the URL of the article collection anchor for the given topic, or for all articles.
 */
function ymir_blog_collection_url(?WP_Term $topic = null): string
{
    return ($topic instanceof WP_Term ? get_category_link($topic) : home_url('/')).'#articles';
}

/**
 * Get the topic category of the current collection page.
 */
function ymir_blog_current_topic(): ?WP_Term
{
    $term = is_category(array_keys(YMIR_BLOG_TOPICS)) ? get_queried_object() : null;

    return $term instanceof WP_Term ? $term : null;
}

/**
 * Get the topic categories that exist, in navigation order.
 */
function ymir_blog_topics(): array
{
    return array_values(array_filter(array_map('get_category_by_slug', array_keys(YMIR_BLOG_TOPICS)), fn ($term) => $term instanceof WP_Term));
}

/**
 * Get the category that represents a post, preferring a topic category.
 */
function ymir_blog_post_category(int $post_id): ?WP_Term
{
    $categories = get_the_category($post_id);

    foreach ($categories as $category) {
        if (isset(YMIR_BLOG_TOPICS[$category->slug])) {
            return $category;
        }
    }

    return $categories[0] ?? null;
}

/**
 * Get the display label of a post category.
 */
function ymir_blog_category_label(WP_Term $category): string
{
    return YMIR_BLOG_TOPICS[$category->slug] ?? $category->name;
}

/**
 * Get the 8:5 cover of a post.
 *
 * The bundled illustration sits beneath the featured image so it shows when the post has no featured
 * image or when the image fails to load (`assets/js/cover.js` removes broken images).
 */
function ymir_blog_cover(int $post_id): string
{
    $image = get_the_post_thumbnail($post_id, 'large', [
        'class' => 'blog-cover-image',
        'decoding' => 'async',
        'fetchpriority' => 'high',
        'loading' => false,
        'sizes' => YMIR_BLOG_COVER_SIZES,
    ]);

    return sprintf(
        '<div class="blog-cover"><img src="%s" srcset="%s" sizes="%s" alt="" width="1200" height="751" decoding="async">%s</div>',
        esc_url(get_theme_file_uri('assets/images/blog-cover-technical.webp')),
        esc_attr(sprintf(
            '%s 600w, %s 900w, %s 1200w',
            esc_url(get_theme_file_uri('assets/images/blog-cover-technical-600.webp')),
            esc_url(get_theme_file_uri('assets/images/blog-cover-technical-900.webp')),
            esc_url(get_theme_file_uri('assets/images/blog-cover-technical.webp'))
        )),
        YMIR_BLOG_COVER_SIZES,
        $image
    );
}

/**
 * Print the Ymir arrow icon.
 */
function ymir_blog_arrow(bool $strong = false): void
{
    printf('<svg class="arrow" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 13 13 3M6 3h7v7" stroke="currentColor" stroke-width="%s"/></svg>', $strong ? '1.5' : '1.1');
}

/**
 * Print a vertical section index rail.
 */
function ymir_blog_section_index(string $number, string $label, bool $dark = false): void
{
    printf(
        '<aside class="section-index%s" aria-hidden="true"><b>%s</b><span>%s</span></aside>',
        $dark ? ' section-index-dark' : '',
        esc_html($number),
        esc_html($label)
    );
}
