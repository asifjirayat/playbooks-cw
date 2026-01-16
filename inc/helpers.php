<?php

/**
 * Load a front-page section template by slug.
 * Example: cw_section('hero') → loads templates/frontpage/hero.php
 */
function cw_section($slug, $args = [])
{
    $file = get_stylesheet_directory() . "/templates/frontpage/{$slug}.php";

    if (file_exists($file)) {
        // Make variables available inside the template
        if (!empty($args)) extract($args);
        include $file;
    } else {
        error_log("CW: Missing frontpage section → {$slug}.php");
    }
}
