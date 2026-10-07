<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

vue_theme_api_get('/site', function () {
    return vue_theme_get_site_info();
});

vue_theme_api_get('/menus/{location}', function (WP_REST_Request $request) {
    if (!array_key_exists($request['location'], get_registered_nav_menus())) {
        return new WP_Error('vue_theme_unknown_menu', 'Unknown menu location.', ['status' => 404]);
    }

    return vue_theme_get_menu($request['location']);
});

// Deprecated since 2.0.0, use vue-theme/v1/site
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/site-title', [
        'methods'  => 'GET',
        'callback' => 'vue_theme_rest_get_site_title',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('wp/v2', '/logo', [
        'methods'  => 'GET',
        'callback' => 'vue_theme_rest_get_custom_logo',
        'permission_callback' => '__return_true',
    ]);
});

function vue_theme_get_site_info() {
    $logo_id = (int) get_theme_mod('custom_logo');
    $image   = $logo_id ? wp_get_attachment_image_src($logo_id, 'full') : false;

    return [
        'title'       => wp_specialchars_decode(get_bloginfo('name'), ENT_QUOTES),
        'description' => wp_specialchars_decode(get_bloginfo('description'), ENT_QUOTES),
        'url'         => home_url('/'),
        'language'    => get_bloginfo('language'),
        'logo'        => $image ? [
            'url'    => $image[0],
            'width'  => (int) $image[1],
            'height' => (int) $image[2],
            'alt'    => (string) get_post_meta($logo_id, '_wp_attachment_image_alt', true),
        ] : null,
    ];
}

// Menu items of a location, nested under `children`
function vue_theme_get_menu($location) {
    $locations = get_nav_menu_locations();

    if (empty($locations[$location])) {
        return [];
    }

    $items = wp_get_nav_menu_items($locations[$location]);

    if (!$items) {
        return [];
    }

    $nodes = [];
    foreach ($items as $item) {
        $nodes[$item->ID] = [
            'id'       => (int) $item->ID,
            'parent'   => (int) $item->menu_item_parent,
            'title'    => html_entity_decode(wp_strip_all_tags($item->title), ENT_QUOTES, 'UTF-8'),
            'url'      => $item->url,
            'target'   => $item->target,
            'classes'  => array_values(array_filter((array) $item->classes)),
            'children' => [],
        ];
    }

    $tree = [];
    foreach ($nodes as &$node) {
        if ($node['parent'] && isset($nodes[$node['parent']])) {
            $nodes[$node['parent']]['children'][] = &$node;
        } else {
            $tree[] = &$node;
        }
    }
    unset($node);

    return $tree;
}

// Callback function to return the site title
function vue_theme_rest_get_site_title() {
    return new WP_REST_Response([
        'site_title' => get_bloginfo('name')
    ], 200);
}

// Callback function to return the logo URL
function vue_theme_rest_get_custom_logo(WP_REST_Request $request) {
    $site = vue_theme_get_site_info();

    if (!$site['logo']) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'No custom logo set.',
        ], 404);
    }

    return new WP_REST_Response([
        'success' => true,
        'logo_url' => $site['logo']['url'],
    ], 200);
}

# Add your custom API routes here

// Sample route used on the home page: /wp-json/vue-theme/v1/hello?name=Vue
vue_theme_api_get('/hello', function (WP_REST_Request $request) {
    $name = sanitize_text_field($request['name'] ?? '') ?: 'World';

    return [
        'message' => sprintf('Hello %s, this comes from functions/api.php', $name),
    ];
});
