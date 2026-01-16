<?php

/**
 * Template Name: All Audiobooks
 */
?>

<?php
get_header();

// Pagination
$paged = max(1, get_query_var('paged') ?: get_query_var('page'));
?>

<main class="pb-24">

    <!-- HERO -->
    <section class="bg-gradient-to-b from-brand-darker to-ui-bg border-b border-ui-border">
        <div class="max-w-7xl mx-auto px-4 py-16">

            <!-- Breadcrumb -->
            <div class="text-xs font-medium text-ui-subtext uppercase tracking-wider mb-4">
                <a href="<?php echo esc_url(home_url('/library')); ?>"
                    class="hover:text-ui-text transition">
                    Library
                </a>
                <span class="mx-2">/</span>
                <span class="text-ui-text">All Audiobooks</span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl lg:text-5xl font-bold text-ui-text mb-4">
                All Audiobooks
            </h1>

            <!-- Description -->
            <p class="max-w-2xl text-ui-subtext text-base leading-relaxed">
                Browse the complete library of audiobook summaries across all topics.
            </p>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-12 gap-8">

            <!-- SIDEBAR: TOPICS -->
            <aside class="col-span-12 lg:col-span-3 self-start">
                <div class="sticky top-28">
                    <div class="bg-ui-surface/40 backdrop-blur border border-ui-border rounded-xl p-6">

                        <h3 class="text-xs font-bold text-ui-subtext uppercase tracking-wider mb-4">
                            Topics
                        </h3>

                        <div class="flex flex-wrap gap-2">
                            <?php
                            $topics = get_terms([
                                'taxonomy'   => 'topics',
                                'hide_empty' => true,
                            ]);

                            if (!empty($topics) && !is_wp_error($topics)):
                                foreach ($topics as $topic): ?>
                                    <a href="<?php echo esc_url(get_term_link($topic)); ?>"
                                        class="px-3 py-1 text-xs font-semibold rounded-full
                                              bg-ui-bg border border-ui-border text-ui-subtext
                                              hover:text-ui-text hover:border-ui-text transition">
                                        <?php echo esc_html($topic->name); ?>
                                    </a>
                            <?php endforeach;
                            endif;
                            ?>
                        </div>

                    </div>
                </div>
            </aside>

            <!-- MAIN GRID -->
            <div class="col-span-12 lg:col-span-9">

                <?php
                $query = new WP_Query([
                    'post_type'      => 'audiobook',
                    'posts_per_page' => 20,
                    'paged'          => $paged,
                    'no_found_rows'  => false,
                ]);
                ?>

                <?php if ($query->have_posts()): ?>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">

                        <?php
                        while ($query->have_posts()):
                            $query->the_post();

                            // Card context
                            $show_topics = true;
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
                            'total'   => $query->max_num_pages,
                            'current' => $paged,
                            'type'    => 'list',
                            'prev_text'  => '<i class="fa-solid fa-chevron-left"></i>',
                            'next_text'  => '<i class="fa-solid fa-chevron-right"></i>',
                        ]);
                        ?>
                    </div>

                <?php else: ?>

                    <p class="text-ui-subtext text-center">
                        No audiobooks found.
                    </p>

                <?php endif; ?>

            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>