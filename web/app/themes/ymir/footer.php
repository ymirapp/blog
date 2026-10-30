</main>
<footer class="site-footer">
    <div class="site-footer-brand">
        <a class="site-brand" href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/'); ?>" aria-label="<?php esc_attr_e('Ymir home', 'ymir'); ?>">
            <?php get_template_part('template-parts/logo'); ?>
        </a>
        <p><?php esc_html_e('Serverless WordPress & PHP applications on AWS', 'ymir'); ?></p>
    </div>
    <div class="site-footer-column">
        <h3><?php esc_html_e('Product', 'ymir'); ?></h3>
        <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/case-studies'); ?>"><?php esc_html_e('Case Studies', 'ymir'); ?></a>
        <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/agencies'); ?>"><?php esc_html_e('For Agencies', 'ymir'); ?></a>
        <a href="https://docs.ymirapp.com/"><?php esc_html_e('Documentation', 'ymir'); ?></a>
    </div>
    <div class="site-footer-column">
        <h3><?php esc_html_e('Legal', 'ymir'); ?></h3>
        <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/terms'); ?>"><?php esc_html_e('Terms of Service', 'ymir'); ?></a>
    </div>
    <div class="site-footer-column">
        <h3><?php esc_html_e('Company', 'ymir'); ?></h3>
        <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/about'); ?>"><?php esc_html_e('About', 'ymir'); ?></a>
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Blog', 'ymir'); ?></a>
        <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/changelog'); ?>"><?php esc_html_e('Changelog', 'ymir'); ?></a>
        <a href="<?php echo esc_url(YMIR_BLOG_MAIN_SITE_URL.'/reports'); ?>"><?php esc_html_e('Reports', 'ymir'); ?></a>
    </div>
    <div class="site-footer-bottom">
        <p>&copy; 2020 - <?php echo esc_html(wp_date('Y')); ?> <?php esc_html_e('Ymir, Inc. All rights reserved.', 'ymir'); ?></p>
        <nav aria-label="<?php esc_attr_e('Social links', 'ymir'); ?>">
            <a href="https://discord.gg/Ze9qHTJWmc">Discord</a>
            <a href="https://github.com/ymirapp">GitHub</a>
            <a href="https://twitter.com/ymirapp">Twitter</a>
            <a href="https://www.youtube.com/channel/UCc9GZVFs-KpnfUQ3MZJU0pQ">YouTube</a>
        </nav>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
