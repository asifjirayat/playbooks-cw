<?php

/**
 * Load custom templates from /templates directory.
 */

add_filter('template_include', function ($template) {

    // Path to your template folder inside the theme
    $theme_dir = get_stylesheet_directory();

    // Single Audiobook Template
    if (is_singular('audiobook')) {
        $custom = $theme_dir . '/templates/single-audiobook.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }

    // Archive: Audiobooks
    if (is_post_type_archive('audiobook')) {
        $custom = $theme_dir . '/templates/archive-audiobook.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }

    // Taxonomy: Topics
    if (is_tax('topics')) {
        $custom = $theme_dir . '/templates/taxonomy-terms.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }

    return $template;
});
