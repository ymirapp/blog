<?php

get_header();
?>
<div class="manual-frame surface-forest">
    <?php ymir_blog_section_index('01', 'Blog / Ymir', true); ?>
    <header class="page-header page-split">
        <h1 class="type-page"><?php esc_html_e('Page not found', 'ymir'); ?></h1>
        <div>
            <a class="text-link" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to all articles', 'ymir'); ?></a>
        </div>
    </header>
</div>
<?php
get_footer();
