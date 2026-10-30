<?php

get_header();

while (have_posts()) :
    the_post();
    $is_post = 'post' === get_post_type();
    $category = $is_post ? ymir_blog_post_category(get_the_ID()) : null;
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-article'); ?>>
        <header class="page-header blog-article-header">
            <?php if ($is_post) : ?>
                <a class="inline-link type-small blog-back-link" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('All articles', 'ymir'); ?></a>
            <?php endif; ?>
            <?php the_title('<h1 class="type-page">', '</h1>'); ?>
            <?php if ($is_post) : ?>
                <div class="type-small text-copy blog-metadata">
                    <span><?php the_author(); ?></span>
                    <?php ymir_blog_publication_date(get_post()); ?>
                    <?php if ($category instanceof WP_Term) : ?>
                        <a class="inline-link" href="<?php echo esc_url(ymir_blog_collection_url($category)); ?>"><?php echo esc_html($category->name); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </header>
        <div class="manual-content blog-article-body">
            <div class="reading-copy prose entry-content">
                <?php
                the_content();

                wp_link_pages([
                    'before' => '<nav class="page-links" aria-label="'.esc_attr__('Page', 'ymir').'">',
                    'after' => '</nav>',
                ]);
                ?>
            </div>
            <?php if ($is_post) : ?>
                <?php
                $related = $category instanceof WP_Term ? get_posts([
                    'category__in' => [$category->term_id],
                    'category__not_in' => ymir_blog_case_study_category_ids(),
                    'post__not_in' => [get_the_ID()],
                    'posts_per_page' => 2,
                    'ignore_sticky_posts' => true,
                ]) : [];
                ?>
                <footer class="blog-article-end">
                    <?php if (!empty($related)) : ?>
                        <?php /* translators: %s: Category name. */ ?>
                        <h2 class="type-subheading"><?php echo esc_html(sprintf(__('More in %s', 'ymir'), ymir_blog_category_label($category))); ?></h2>
                        <ul class="blog-related-articles">
                            <?php foreach ($related as $related_post) : ?>
                                <li><a class="inline-link type-body" href="<?php echo esc_url(get_permalink($related_post)); ?>"><?php echo esc_html(get_the_title($related_post)); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <a class="text-link" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to all articles', 'ymir'); ?></a>
                </footer>
            <?php endif; ?>
        </div>
    </article>
    <?php
endwhile;

get_footer();
