<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once('theme-settings.php');
require_once('api-helpers.php');
require_once('api.php');

// Vue app loader (Vite dev server / production build)
require_once('vite.php');

// Menus
require_once('menu/_loader.php');

// Post Types
require_once('post-types/_loader.php');

// Add other loader files here as needed
