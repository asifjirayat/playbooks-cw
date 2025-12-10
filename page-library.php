<?php
/* Template Name: Library Page */
get_header();
?>

<main class="pt-10 pb-20">

    <?php
    // Dynamic audiobook sections
    get_template_part('templates/library/section-popular');
    get_template_part('templates/library/section-library');   // the A–Z or All Audiobooks section
    get_template_part('templates/library/section-editors');   // editor picks (if used)
    // any other sections
    ?>

</main>

<?php get_footer(); ?>