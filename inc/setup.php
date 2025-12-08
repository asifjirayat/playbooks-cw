<?php

// Minimal theme setup
add_action( 'after_setup_theme', 'cw_child_setup' );
function cw_child_setup() {

    // Enable featured images
    add_theme_support( 'post-thumbnails' );

    // Register a starter menu (optional)
    register_nav_menus([
        'primary' => 'Primary Menu'
    ]);
}
