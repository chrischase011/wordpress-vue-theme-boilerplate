<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists( 'init_custom_post_types' )) {

    function init_custom_post_types() {
        // Register Post Types Here...

        require_once('custom-posttype.php'); // Custom Post Type path
    }

    add_action( 'init', 'init_custom_post_types' );
}
