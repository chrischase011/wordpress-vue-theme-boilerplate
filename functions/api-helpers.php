<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Your routes live under /wp-json/vue-theme/v1/
define( 'VUE_THEME_API_NAMESPACE', 'vue-theme/v1' );

// $options: 'permission' (true = logged in, or a capability like 'edit_posts') and 'args'
function vue_theme_api( $methods, $route, $callback, $options = array() ) {
    $options = wp_parse_args( $options, array(
        'permission' => null,
        'args'       => array(),
    ) );

    // '/books/{id}' -> '/books/(?P<id>[^/]+)'
    $route = '/' . trim( preg_replace( '/\{(\w+)\}/', '(?P<$1>[^/]+)', $route ), '/' );

    $register = function () use ( $methods, $route, $callback, $options ) {
        register_rest_route( VUE_THEME_API_NAMESPACE, $route, array(
            'methods'             => $methods,
            'callback'            => $callback,
            'permission_callback' => vue_theme_api_permission( $options['permission'] ),
            'args'                => $options['args'],
        ) );
    };

    if ( did_action( 'rest_api_init' ) ) {
        $register();
    } else {
        add_action( 'rest_api_init', $register );
    }
}

function vue_theme_api_get( $route, $callback, $options = array() ) {
    vue_theme_api( 'GET', $route, $callback, $options );
}

function vue_theme_api_post( $route, $callback, $options = array() ) {
    vue_theme_api( 'POST', $route, $callback, $options );
}

function vue_theme_api_put( $route, $callback, $options = array() ) {
    vue_theme_api( 'PUT, PATCH', $route, $callback, $options );
}

function vue_theme_api_delete( $route, $callback, $options = array() ) {
    vue_theme_api( 'DELETE', $route, $callback, $options );
}

function vue_theme_api_permission( $permission ) {
    if ( null === $permission || false === $permission ) {
        return '__return_true';
    }

    if ( true === $permission ) {
        return 'is_user_logged_in';
    }

    if ( is_string( $permission ) ) {
        return function () use ( $permission ) {
            return current_user_can( $permission );
        };
    }

    return $permission;
}
