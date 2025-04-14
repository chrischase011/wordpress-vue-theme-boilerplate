<?php

// Register the custom REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/site-title', [
        'methods'  => 'GET',
        'callback' => 'get_site_title',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('wp/v2', '/logo', [
        'methods'  => 'GET',
        'callback' => 'rest_get_custom_logo',
        'permission_callback' => '__return_true',
    ]);

    // Register the custom REST API endpoint for the site title
});

// Callback function to return the site title
function get_site_title() {
    return new WP_REST_Response([
        'site_title' => get_bloginfo('name')
    ], 200);
}

// Callback function to return the logo URL
function rest_get_custom_logo(WP_REST_Request $request) {
    $custom_logo_id = get_theme_mod('custom_logo');

    if (!$custom_logo_id) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'No custom logo set.',
        ], 404);
    }

    $image = wp_get_attachment_image_src($custom_logo_id, 'full');

    return new WP_REST_Response([
        'success' => true,
        'logo_url' => $image[0],
    ], 200);
}

# Add your custom API Callbacks here