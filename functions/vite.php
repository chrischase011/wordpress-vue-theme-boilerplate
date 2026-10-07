<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Must match build.rollupOptions.input in vite.config.js
define( 'VUE_THEME_ENTRY', 'src/main.js' );

// Dev server URL while `npm run dev` is running, empty otherwise
function vue_theme_dev_server_url() {
    static $url = null;

    if ( null !== $url ) {
        return $url;
    }

    $url      = '';
    $hot_file = get_template_directory() . '/.vite-hot';

    if ( ! is_readable( $hot_file ) ) {
        return $url;
    }

    $candidate = untrailingslashit( trim( (string) file_get_contents( $hot_file ) ) );
    $parts     = wp_parse_url( $candidate );

    if ( empty( $parts['host'] ) || empty( $parts['port'] ) ) {
        return $url;
    }

    // Ignore a leftover hot file when nothing is listening
    if ( apply_filters( 'vue_theme_check_dev_server', true ) ) {
        $socket = @fsockopen( $parts['host'], (int) $parts['port'], $errno, $errstr, 0.3 );

        if ( ! $socket ) {
            return $url;
        }

        fclose( $socket );
    }

    $url = $candidate;

    return $url;
}

function vue_theme_manifest() {
    static $manifest = null;

    if ( null !== $manifest ) {
        return $manifest;
    }

    $manifest = [];
    $file     = get_template_directory() . '/dist/manifest.json';

    if ( is_readable( $file ) ) {
        $decoded = json_decode( (string) file_get_contents( $file ), true );

        if ( is_array( $decoded ) ) {
            $manifest = $decoded;
        }
    }

    return $manifest;
}

// CSS of a chunk and of the chunks it imports
function vue_theme_manifest_css( $manifest, $key, &$seen = [] ) {
    if ( isset( $seen[ $key ] ) || empty( $manifest[ $key ] ) ) {
        return [];
    }

    $seen[ $key ] = true;
    $css          = $manifest[ $key ]['css'] ?? [];

    foreach ( $manifest[ $key ]['imports'] ?? [] as $import ) {
        $css = array_merge( $css, vue_theme_manifest_css( $manifest, $import, $seen ) );
    }

    return array_values( array_unique( $css ) );
}

function vue_theme_has_assets() {
    $manifest = vue_theme_manifest();

    return vue_theme_dev_server_url() || ! empty( $manifest[ VUE_THEME_ENTRY ]['file'] );
}

// Available in Vue as window.wpVueTheme (see src/settings.js)
function vue_theme_app_config() {
    $rest     = wp_parse_url( rest_url() );
    $rest_url = ( $rest['path'] ?? '/' ) . ( isset( $rest['query'] ) ? '?' . $rest['query'] : '' );

    $menus = [];
    foreach ( array_keys( get_registered_nav_menus() ) as $location ) {
        $menus[ $location ] = vue_theme_get_menu( $location );
    }

    $config = [
        'restUrl'  => $rest_url,
        'nonce'    => is_user_logged_in() ? wp_create_nonce( 'wp_rest' ) : null,
        'basePath' => trailingslashit( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ),
        'homeUrl'  => home_url( '/' ),
        'themeUrl' => trailingslashit( get_template_directory_uri() ),
        'isDev'    => (bool) vue_theme_dev_server_url(),
        'site'     => vue_theme_get_site_info(),
        'menus'    => $menus,
    ];

    // Use this filter to pass your own data to Vue
    return apply_filters( 'vue_theme_app_config', $config );
}

function vue_theme_enqueue_assets() {
    $dev_server = vue_theme_dev_server_url();

    if ( $dev_server ) {
        // Development (HMR)
        wp_enqueue_script( 'vue-theme-vite-client', $dev_server . '/@vite/client', [], null, false );
        wp_enqueue_script( 'vue-theme-app', $dev_server . '/' . VUE_THEME_ENTRY, [ 'vue-theme-vite-client' ], null, true );
    } else {
        // Production build
        $manifest = vue_theme_manifest();

        if ( empty( $manifest[ VUE_THEME_ENTRY ]['file'] ) ) {
            return;
        }

        $dist_uri = get_template_directory_uri() . '/dist/';

        foreach ( vue_theme_manifest_css( $manifest, VUE_THEME_ENTRY ) as $index => $css_file ) {
            wp_enqueue_style( 'vue-theme-app-' . $index, $dist_uri . $css_file, [], null );
        }

        wp_enqueue_script( 'vue-theme-app', $dist_uri . $manifest[ VUE_THEME_ENTRY ]['file'], [], null, true );
    }

    wp_add_inline_script(
        'vue-theme-app',
        'window.wpVueTheme = ' . wp_json_encode( vue_theme_app_config(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES ) . ';',
        'before'
    );
}
add_action( 'wp_enqueue_scripts', 'vue_theme_enqueue_assets' );

// Vite outputs ES modules, so the script tags need type="module"
function vue_theme_module_script_tag( $tag, $handle ) {
    if ( ! in_array( $handle, [ 'vue-theme-app', 'vue-theme-vite-client' ], true ) ) {
        return $tag;
    }

    return preg_replace_callback(
        '/<script\b(?=[^>]*\ssrc=)([^>]*)>/i',
        function ( $matches ) {
            $attributes = preg_replace( '/\stype=(["\']).*?\1/i', '', $matches[1] );

            return '<script type="module"' . $attributes . '>';
        },
        $tag
    );
}
add_filter( 'script_loader_tag', 'vue_theme_module_script_tag', 10, 2 );

// Shown inside #app until Vue mounts
function vue_theme_app_fallback() {
    if ( ! vue_theme_has_assets() && current_user_can( 'edit_theme_options' ) ) {
        echo '<p style="padding:2rem;font-family:sans-serif">';
        echo wp_kses_post( vue_theme_missing_build_message() );
        echo '</p>';
    }

    echo '<noscript>' . esc_html__( 'This website needs JavaScript to be enabled.', 'text_domain' ) . '</noscript>';
}

function vue_theme_missing_build_message() {
    return sprintf(
        /* translators: %s: theme folder path */
        __( '<strong>The Vue app is not built yet.</strong> Open a terminal in <code>%s</code> and run <code>npm install</code>, then <code>npm run build</code> (or <code>npm run dev</code> while developing).', 'text_domain' ),
        esc_html( get_template_directory() )
    );
}

function vue_theme_missing_build_notice() {
    if ( vue_theme_has_assets() || ! current_user_can( 'edit_theme_options' ) ) {
        return;
    }

    echo '<div class="notice notice-error"><p>' . wp_kses_post( vue_theme_missing_build_message() ) . '</p></div>';
}
add_action( 'admin_notices', 'vue_theme_missing_build_notice' );
