<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function vue_theme_custom_admin_menu()
{
    add_menu_page("Custom Menu", "Custom Menu", "manage_options", 'custom-menu', 'vue_theme_custom_admin_menu_page', 'dashicons-info', 26); // Set add_menu_page parameters
}

function vue_theme_custom_admin_menu_page()
{
    echo '<div class="wrap"><h1>' . esc_html( get_admin_page_title() ) . '</h1><p>Edit functions/menu/about-menu.php to build this page.</p></div>';
}

add_action('admin_menu', 'vue_theme_custom_admin_menu'); // Hook into the admin menu action
