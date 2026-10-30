<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'ymir'); ?></a>
<header class="site-header">
    <a class="site-brand" href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/'); ?>" aria-label="<?php esc_attr_e('Ymir home', 'ymir'); ?>">
        <?php get_template_part('template-parts/logo'); ?>
    </a>
    <nav class="site-nav" aria-label="<?php esc_attr_e('Primary navigation', 'ymir'); ?>">
        <a href="https://docs.ymirapp.com/"><?php esc_html_e('Documentation', 'ymir'); ?></a>
        <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/agencies'); ?>"><?php esc_html_e('For Agencies', 'ymir'); ?></a>
        <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/vector'); ?>"><?php esc_html_e('For Hosting Companies', 'ymir'); ?></a>
    </nav>
    <details class="site-menu">
        <summary>
            <span class="site-menu-open"><?php esc_html_e('Menu', 'ymir'); ?></span>
            <span class="site-menu-close"><?php esc_html_e('Close', 'ymir'); ?><span class="screen-reader-text"> <?php esc_html_e('menu', 'ymir'); ?></span></span>
        </summary>
        <div class="site-menu-panel">
            <a href="https://docs.ymirapp.com/"><?php esc_html_e('Documentation', 'ymir'); ?></a>
            <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/agencies'); ?>"><?php esc_html_e('For Agencies', 'ymir'); ?></a>
            <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/vector'); ?>"><?php esc_html_e('For Hosting Companies', 'ymir'); ?></a>
            <a class="site-menu-account" href="https://www.ymirapp.com/login"><?php esc_html_e('Log in', 'ymir'); ?> <?php ymir_blog_arrow(); ?></a>
        </div>
    </details>
    <a class="site-account" href="https://www.ymirapp.com/login"><?php esc_html_e('Log in', 'ymir'); ?> <?php ymir_blog_arrow(true); ?></a>
</header>
<main id="content" class="manual-page">
