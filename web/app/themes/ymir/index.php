<?php

global $wp_query;

get_header();

$current_topic = ymir_blog_current_topic();
$description = __('Guides and perspectives on running WordPress and PHP on serverless AWS.', 'ymir');
$title = __('Blog', 'ymir');

if (is_search()) {
    /* translators: %s: Search query. */
    $title = sprintf(__('Search results for “%s”', 'ymir'), get_search_query(false));
    $description = '';
} elseif (is_archive() && !$current_topic instanceof WP_Term) {
    $title = wp_strip_all_tags(get_the_archive_title());
    $description = wp_strip_all_tags(get_the_archive_description());
}

$paged = max(1, (int) get_query_var('paged'));
$posts_per_page = (int) $wp_query->get('posts_per_page');
$offset = $posts_per_page > 0 ? ($paged - 1) * $posts_per_page : 0;
$found_posts = (int) $wp_query->found_posts;
$featured = is_home() && 1 === $paged && have_posts();
?>
<div class="manual-frame surface-forest">
    <?php ymir_blog_section_index('01', 'Blog / Ymir', true); ?>
    <header class="page-header page-split">
        <h1 class="type-page"><?php echo esc_html($title); ?></h1>
        <?php if (!empty($description)) : ?>
            <p class="page-lead"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
    </header>
</div>
<div class="manual-frame">
    <?php ymir_blog_section_index('02', 'Articles / Ideas'); ?>
    <section class="manual-content blog-content" aria-label="<?php esc_attr_e('Articles', 'ymir'); ?>">
        <?php if ($featured) : ?>
            <?php
            the_post();
            $category = ymir_blog_post_category(get_the_ID());
            ?>
            <article <?php post_class('blog-featured'); ?> aria-labelledby="latest-article">
                <?php /* translators: %s: Article title. */ ?>
                <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Read %s', 'ymir'), the_title_attribute(['echo' => false]))); ?>">
                    <?php echo ymir_blog_cover(get_the_ID()); ?>
                </a>
                <div class="blog-featured-content">
                    <h2 class="type-editorial" id="latest-article">
                        <a class="blog-article-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="type-caption text-copy blog-row-metadata">
                        <span><?php esc_html_e('Latest article', 'ymir'); ?><?php echo $category instanceof WP_Term ? ' · '.esc_html($category->name) : ''; ?></span>
                        <?php ymir_blog_publication_date(get_post()); ?>
                    </div>
                    <p class="type-body text-copy"><?php echo esc_html(ymir_blog_excerpt(get_post())); ?></p>
                    <a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read the article', 'ymir'); ?></a>
                </div>
            </article>
        <?php endif; ?>
        <div class="blog-collection" id="articles">
            <div class="blog-collection-heading">
                <nav class="archive-years blog-topics" aria-label="<?php esc_attr_e('Article topics', 'ymir'); ?>">
                    <a href="<?php echo esc_url(ymir_blog_collection_url()); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>><?php esc_html_e('All articles', 'ymir'); ?></a>
                    <?php foreach (ymir_blog_topics() as $topic) : ?>
                        <a href="<?php echo esc_url(ymir_blog_collection_url($topic)); ?>"<?php echo $current_topic instanceof WP_Term && $current_topic->term_id === $topic->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html(ymir_blog_category_label($topic)); ?></a>
                    <?php endforeach; ?>
                </nav>
                <p class="type-small text-copy">
                    <?php
                    if ($wp_query->max_num_pages > 1) {
                        /* translators: 1: First article number. 2: Last article number. 3: Total number of articles. */
                        printf(esc_html__('%1$d–%2$d of %3$d articles', 'ymir'), $offset + 1, $offset + $wp_query->post_count, $found_posts);
                    } else {
                        /* translators: %d: Total number of articles. */
                        printf(esc_html(_n('%d article', '%d articles', $found_posts, 'ymir')), $found_posts);
                    }
                    ?>
                </p>
            </div>
            <div class="blog-article-list">
                <?php while (have_posts()) : ?>
                    <?php
                    the_post();
                    $category = ymir_blog_post_category(get_the_ID());
                    ?>
                    <article <?php post_class('blog-article-row'); ?>>
                        <h2 class="type-subheading">
                            <a class="blog-article-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="type-caption text-copy blog-row-metadata">
                            <?php if ($category instanceof WP_Term) : ?>
                                <span><?php echo esc_html($category->name); ?></span>
                            <?php endif; ?>
                            <?php ymir_blog_publication_date(get_post()); ?>
                        </div>
                        <p class="type-body text-copy"><?php echo esc_html(ymir_blog_excerpt(get_post())); ?></p>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php if ($wp_query->max_num_pages > 1) : ?>
                <?php
                $previous_url = get_previous_posts_page_link();
                $next_url = get_next_posts_page_link();
                ?>
                <nav class="blog-pagination" aria-label="<?php esc_attr_e('Article pages', 'ymir'); ?>">
                    <?php if ($paged > 1 && !empty($previous_url)) : ?>
                        <a class="text-link" rel="prev" href="<?php echo esc_url($previous_url.'#articles'); ?>"><?php esc_html_e('Previous', 'ymir'); ?></a>
                    <?php else : ?>
                        <span class="text-link" aria-disabled="true"><?php esc_html_e('Previous', 'ymir'); ?></span>
                    <?php endif; ?>
                    <p class="type-small text-copy">
                        <?php
                        /* translators: 1: Current page number. 2: Total number of pages. */
                        printf(esc_html__('Page %1$d of %2$d', 'ymir'), $paged, (int) $wp_query->max_num_pages);
                        ?>
                    </p>
                    <?php if ($paged < $wp_query->max_num_pages && !empty($next_url)) : ?>
                        <a class="text-link" rel="next" href="<?php echo esc_url($next_url.'#articles'); ?>"><?php esc_html_e('Next', 'ymir'); ?></a>
                    <?php else : ?>
                        <span class="text-link" aria-disabled="true"><?php esc_html_e('Next', 'ymir'); ?></span>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </div>
    </section>
</div>
<div class="manual-frame surface-forest">
    <?php ymir_blog_section_index('03', 'Customers / Stories', true); ?>
    <section class="manual-content page-split" aria-labelledby="customer-stories">
        <h2 class="type-section" id="customer-stories"><?php esc_html_e('Ymir in production', 'ymir'); ?></h2>
        <div class="blog-related-intro">
            <p class="page-lead"><?php esc_html_e('Read how Open House New York and JOE Media Group use Ymir.', 'ymir'); ?></p>
            <a class="text-link" href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/case-studies'); ?>"><?php esc_html_e('Explore the case studies', 'ymir'); ?></a>
        </div>
    </section>
</div>
<?php
get_footer();
