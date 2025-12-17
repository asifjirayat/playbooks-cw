<?php
/* Template Name: Library Page */
get_header();
?>

<main class="pt-10 pb-20">
    <?php
    // Library Hero
    get_template_part('templates/library/section-library-hero');

    // Main Library Content
    get_template_part('templates/library/section-library'); ?>
</main>

<?php get_footer(); ?>