<?php
/* Template Name: Library Page */
get_header();
?>

<main class="pt-10 pb-20">

    <?php
    // Include the same dynamic audiobook sections we built for front-page
    get_template_part('templates/frontpage/section-popular');
    get_template_part('templates/frontpage/section-library');   // the A–Z or All Audiobooks section
    get_template_part('templates/frontpage/section-editors');   // editor picks (if used)
    // any other sections
    ?>

</main>

<?php get_footer(); ?>