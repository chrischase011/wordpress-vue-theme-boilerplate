<?php

$labels = array(
    'name'                  => _x('Custom Post Type', 'Post Type General Name', 'text_domain'),
    'singular_name'         => _x('Custom Post Type Section', 'Post Type Singular Name', 'text_domain'),
    'menu_name'             => __('Custom Post Type Section', 'text_domain'),
    'name_admin_bar'        => __('Custom Post Type Section', 'text_domain'),
    'archives'              => __('Item Archives', 'text_domain'),
    'attributes'            => __('Set Order', 'text_domain'),
    'parent_item_colon'     => __('Parent Item:', 'text_domain'),
    'all_items'             => __('All Custom Post Type Section', 'text_domain'),
    'add_new_item'          => __('Add Custom Post Type Section', 'text_domain'),
    'add_new'               => __('Add New', 'text_domain'),
    'new_item'              => __('New Item', 'text_domain'),
    'edit_item'             => __('Edit Item', 'text_domain'),
    'update_item'           => __('Update Item', 'text_domain'),
    'view_item'             => __('View Item', 'text_domain'),
    'view_items'            => __('View Items', 'text_domain'),
    'search_items'          => __('Search Item', 'text_domain'),
    'not_found'             => __('No Item found', 'text_domain'),
    'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
    'featured_image'        => __('Photo', 'text_domain'),
    'set_featured_image'    => __('Set photo', 'text_domain'),
    'remove_featured_image' => __('Remove photo', 'text_domain'),
    'use_featured_image'    => __('Use as photo', 'text_domain'),
    'insert_into_item'      => __('Insert into item', 'text_domain'),
    'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
    'items_list'            => __('Items list', 'text_domain'),
    'items_list_navigation' => __('Items list navigation', 'text_domain'),
    'filter_items_list'     => __('Filter items list', 'text_domain'),
);
$args = array(
    'label'                 => __('Custom Post Type Section', 'text_domain'), // Change this to your desired label
    'labels'                => $labels, // Use the labels array defined above
    'supports'              => array('title', 'thumbnail'), // Add other supported features as needed
    'taxonomies'            => array(), // Add custom taxonomies if needed
    'hierarchical'          => false, // Set to true if you want hierarchical behavior (like pages)
    'public'                => true, // Set to true to make it publicly accessible
    'show_ui'               => true, // Set to true to show in admin UI
    // 'show_in_menu'          => 'home-menu', // Use a custom menu slug or 'edit.php' for the default menu
    'menu_position'         => 26, // Set the position in the admin menu (26 is below Posts)
    // 'menu_icon'             => 'dashicons-format-image', // Use a custom icon or a dashicon
    // 'show_in_rest'         => true, // Set to true to show in REST API
    'show_in_admin_bar'     => true, // Set to true to show in admin bar
    'show_in_nav_menus'     => true, // Set to true to show in navigation menus
    'can_export'            => true, // Set to true to allow export
    'has_archive'           => true, // Set to true to enable archive page
    'exclude_from_search'   => true, // Set to true to exclude from search results
    'publicly_queryable'    => true, // Set to true to allow queries
    'capability_type'       => 'page', // Set the capability type (e.g., 'post', 'page', or a custom capability type)
);
register_post_type('custom-post-type', $args); // Change 'custom-post-type' to your desired post type slug (Max 20 characters, lowercase, no spaces or special characters)