<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue parent + child styles and scripts
 */
add_action( 'wp_enqueue_scripts', 'cw_child_enqueue_scripts', 20 );
function cw_child_enqueue_scripts() {

    // Load parent stylesheet
    wp_enqueue_style(
        'hello-elementor-style',
        get_template_directory_uri() . '/style.css'
    );

    // Load child theme stylesheet
    wp_enqueue_style(
        'cw-child-style',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        array('hello-elementor-style'),
        filemtime( get_stylesheet_directory() . '/assets/css/main.css' )
    );

    // Load custom JS
    wp_enqueue_script(
        'cw-child-js',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array(),
        filemtime( get_stylesheet_directory() . '/assets/js/main.js' ),
        true
    );
}

/**
 * Load minimal theme setup
 */
require_once get_stylesheet_directory() . '/inc/setup.php';

/**
 * Register WP Menus
 */
function cw_register_menus() {
    register_nav_menus([
        'primary_menu'   => __('Primary Menu', 'cw'),
        'footer_menu'    => __('Footer Menu', 'cw'),
    ]);
}
add_action('after_setup_theme', 'cw_register_menus');

/**
 * Custom Logo
 */
function cw_theme_supports() {
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 120,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
}

add_action('after_setup_theme', 'cw_theme_supports');
