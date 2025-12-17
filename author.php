<?php

/**
 * Author Archive Template
 */

get_header();

// Current author from URL: /author/{slug}
$author_slug = get_query_var('cw_author');
$author_name = ucwords(str_replace('-', ' ', $author_slug));

// Pagination
$paged = max(1, get_query_var('paged'));

// Query audiobooks by author ACF field
$query = new WP_Query([
    'post_type'      => 'audiobook',
    'posts_per_page' => 20,
    'paged'          => $paged,
    'meta_query'     => [
        [
            'key'     => 'book_author',
            'value'   => $author_name,
            'compare' => 'LIKE',
        ],
    ],
]);
?>

<main class="pb-24">

    <!-- AUTHOR HERO -->
    <section class="bg-gradient-to-b from-brand-darker to-ui-bg border-b border-ui-border">
        <div class="max-w-7xl mx-auto px-4 py-16">

            <!-- Breadcrumb -->
            <div class="text-xs font-medium text-ui-subtext uppercase tracking-wider mb-4">
                <a href="<?php echo esc_url(home_url('/library')); ?>"
                    class="hover:text-ui-text transition no-underline hover:no-underline">
                    Library
                </a>
                <span class="mx-2">/</span>
                <span class="text-ui-text">Author</span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl lg:text-5xl font-bold text-ui-text mb-4">
                <?php echo esc_html($author_name); ?>
            </h1>

            <p class="max-w-2xl text-ui-subtext text-base leading-relaxed">
                Audiobook summaries written by <?php echo esc_html($author_name); ?>.
            </p>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-12 gap-8">

            <?php if ($query->have_posts()): ?>

                <!-- SIDEBAR -->
                <div class="col-span-12 lg:col-span-3 self-start sticky top-28">
                    <?php get_template_part('templates/parts/sidebar-author'); ?>
                </div>

                <!-- MAIN GRID -->
                <div class="col-span-12 lg:col-span-9">

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">

                        <?php
                        while ($query->have_posts()):
                            $query->the_post();

                            // Hide author label in card context
                            $show_author = false;

                            get_template_part('templates/parts/audiobook-card');
                        endwhile;

                        wp_reset_postdata();
                        ?>

                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-16 flex justify-center">
                        <?php
                        echo paginate_links([
                            'total'   => $query->max_num_pages,
                            'current' => $paged,
                            'type'    => 'list',
                        ]);
                        ?>
                    </div>

                </div>

            <?php else: ?>

                <p class="col-span-12 text-ui-subtext text-center">
                    No audiobooks found for this author.
                </p>

            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>