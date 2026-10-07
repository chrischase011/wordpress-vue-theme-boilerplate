<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function vue_theme_load_theme_settings() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'script', 'style' ) );
    add_theme_support( 'custom-logo' );

    // Assign menus in Appearance > Menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'text_domain' ),
        'footer'  => __( 'Footer Menu', 'text_domain' ),
    ) );
}

add_action( 'after_setup_theme', 'vue_theme_load_theme_settings' );


function vue_theme_load_custom_scripts() {
    wp_enqueue_style('wp-block-library');
    // add custom styles here and scripts here
}
add_action('wp_enqueue_scripts', 'vue_theme_load_custom_scripts');


// Vue Router handles the routes, don't let WordPress guess a redirect
add_filter( 'do_redirect_guess_404_permalink', '__return_false' );

// Vue-only routes are a 404 for WordPress, keep "Page not found" out of the tab title
function vue_theme_document_title_parts( $parts ) {
    if ( is_404() ) {
        $parts = array( 'title' => get_bloginfo( 'name', 'display' ) );
    }

    return $parts;
}
add_filter( 'document_title_parts', 'vue_theme_document_title_parts' );
