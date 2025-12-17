<?php

/**
 * Load custom templates from /templates directory.
 */
add_filter('template_include', function ($template) {

    // IMPORTANT: never override normal pages
    if (is_page()) {
        return $template;
    }

    $theme_dir = get_stylesheet_directory();

    // Single Audiobook
    if (is_singular('audiobook')) {
        $custom = $theme_dir . '/templates/single-audiobook.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }

    // Topics Taxonomy
    if (is_tax('topics')) {
        $custom = $theme_dir . '/templates/taxonomy-terms.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }

    return $template;
});


/**
 * Redirect the archive instead
 */
add_action('template_redirect', function () {
    if (is_post_type_archive('audiobook') && ! is_page('audiobooks')) {
        wp_redirect(home_url('/audiobooks'), 301);
        exit;
    }
});


/**
 * Custom Author Routing: /authors/{slug}
 */
add_action('init', function () {
    add_rewrite_rule(
        '^authors/([^/]+)/?$',
        'index.php?cw_author=$matches[1]',
        'top'
    );
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'cw_author';
    return $vars;
});

/**
 * Load author.php for custom author routes
 */
add_action('template_redirect', function () {
    if (get_query_var('cw_author')) {
        $template = get_stylesheet_directory() . '/author.php';
        if (file_exists($template)) {
            include $template;
            exit;
        }
    }
});
