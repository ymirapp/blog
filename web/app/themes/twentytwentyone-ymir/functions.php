<?php

function ymir_twenty_twenty_one_enqueue_styles() {
    $parentHandle = 'twenty-twenty-one-style';
    $theme = wp_get_theme();

    wp_enqueue_style($parentHandle, get_template_directory_uri().'/style.css', [], $theme->parent()->get('Version'));
    wp_enqueue_style('ymir-style', get_stylesheet_uri(), [$parentHandle], $theme->get('Version'));

    if (is_single()) {
        wp_enqueue_style('highlight-style', get_stylesheet_directory_uri().'/assets/css/highlight.min.css');
    }
}
add_action('wp_enqueue_scripts', 'ymir_twenty_twenty_one_enqueue_styles');

function ymir_twenty_twenty_one_enqueue_scripts()
{
    if (is_single()) {
        wp_enqueue_script('child-script-headerbar', get_stylesheet_directory_uri().'/assets/js/headerbar.js', ['jquery']);
        wp_enqueue_script('child-script-highlight', get_stylesheet_directory_uri().'/assets/js/highlight.js');
    }
}
add_action('wp_enqueue_scripts', 'ymir_twenty_twenty_one_enqueue_scripts');
