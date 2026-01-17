<?php

/**
 * Topics Taxonomy Archive Template
 */

get_header();

// Current term
$term = get_queried_object();

if (!$term || is_wp_error($term)) {
    get_footer();
    return;
}

// Pagination
$paged = max(1, get_query_var('paged') ?: get_query_var('page'));

$query = new WP_Query([
    'post_type'      => 'audiobook',
    'posts_per_page' => 20,
    'paged'          => $paged,
    'no_found_rows'  => false,
    'tax_query'      => [
        [
            'taxonomy' => 'topics',
            'field'    => 'term_id',
            'terms'    => $term->term_id,
        ],
    ],
]);
?>

<main class="pb-24">

    <!-- CATEGORY HERO -->
    <section class="bg-gradient-to-b from-brand-darker to-ui-bg border-b border-ui-border">
        <div class="max-w-7xl mx-auto px-4 py-16">

            <!-- Breadcrumb -->
            <div class="text-xs font-medium text-ui-subtext uppercase tracking-wider mb-4">
                <a href="<?php echo esc_url(home_url('/library')); ?>"
                    class="hover:text-ui-text transition">
                    Library
                </a>
                <span class="mx-2">/</span>
                <span class="text-ui-text"><?php echo esc_html($term->name); ?></span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl lg:text-5xl font-bold text-ui-text mb-4">
                <?php echo esc_html($term->name); ?>
            </h1>

            <!-- Description -->
            <?php if (!empty($term->description)): ?>
                <p class="max-w-2xl text-ui-subtext text-base leading-relaxed">
                    <?php echo esc_html($term->description); ?>
                </p>
            <?php endif; ?>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-12 gap-8">

            <?php if ($query->have_posts()): ?>

                <!-- SIDEBAR -->
                <div class="col-span-12 lg:col-span-3 self-start sticky top-28">
                    <?php get_template_part('templates/parts/sidebar-topic'); ?>
                </div>

                <!-- MAIN GRID -->
                <div class="col-span-12 lg:col-span-9">

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">

                        <?php
                        while ($query->have_posts()):
                            $query->the_post();

                            // Card context
                            $show_topics = false;
                            $show_author = true;

                            get_template_part('templates/parts/audiobook-card');
                        endwhile;

                        wp_reset_postdata();
                        ?>

                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-16 flex justify-center">
                        <?php
                        echo paginate_links([
                            'total'      => $query->max_num_pages,
                            'current'    => $paged,
                            'type'       => 'list',
                            'prev_text'  => '<span class="sr-only">Previous</span><i class="fa-solid fa-chevron-left"></i>',
                            'next_text'  => '<span class="sr-only">Next</span><i class="fa-solid fa-chevron-right"></i>',
                        ]);
                        ?>
                    </div>

                </div>

            <?php else: ?>

                <p class="col-span-12 text-ui-subtext text-center">
                    No audiobooks found in this topic.
                </p>

            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>