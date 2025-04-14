<?php

function _theme_custom_admin_menu()
{
    add_menu_page("Custom Menu", "Custom Menu", "manage_options", 'custom-menu', '', 'dashicons-info', 26); // Set add_menu_page parameters
}

add_action('admin_menu', '_theme_custom_admin_menu'); // Hook into the admin menu action