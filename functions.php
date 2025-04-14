<?php 

require('functions/loader.php');


function load_vite_assets() {
    $dist_path = get_template_directory_uri() . '/dist/';

    // Automatically load the latest hashed JS and CSS files
    foreach (glob(get_template_directory() . '/dist/assets/index-*.js') as $file) {
        wp_enqueue_script('theme-js', $dist_path . 'assets/' . basename($file), [], null, true);
    }

    foreach (glob(get_template_directory() . '/dist/assets/index-*.css') as $file) {
        wp_enqueue_style('theme-css', $dist_path . 'assets/' . basename($file));
    }
}
add_action('wp_enqueue_scripts', 'load_vite_assets');
