<?php

function load_custom_theme_settings() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );
}

add_action( 'after_setup_theme', 'load_custom_theme_settings' );


function load_custom_scripts() {
    wp_enqueue_style('wp-block-library');
    // add custom styles here and scripts here
}
add_action('wp_enqueue_scripts', 'load_custom_scripts');