<?php

/**
 * Library – Topic Driven Sections
 */

$topics = get_terms([
    'taxonomy'   => 'topics',
    'hide_empty' => true,
]);

if (empty($topics) || is_wp_error($topics)) {
    return;
}
?>

<section class="max-w-7xl mx-auto px-4 py-16 space-y-20">

    <?php foreach ($topics as $topic): ?>

        <?php
        // Query audiobooks for this topic
        $books = new WP_Query([
            'post_type'      => 'audiobook',
            'posts_per_page' => 4, // preview count
            'tax_query'      => [
                [
                    'taxonomy' => 'topics',
                    'field'    => 'term_id',
                    'terms'    => $topic->term_id,
                ],
            ],
        ]);

        if (!$books->have_posts()) {
            continue;
        }
        ?>

        <section>

            <!-- Section Header -->
            <div class="flex items-center justify-between mb-8">

                <h2 class="text-3xl lg:text-4xl font-bold text-ui-text">
                    <?= esc_html($topic->name); ?>
                </h2>

                <a href="<?= esc_url(get_term_link($topic)); ?>"
                    class="text-sm font-semibold text-brand-yellow hover:text-brand-yellow/80">
                    View Category <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

            <!-- Audiobook Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">

                <?php
                while ($books->have_posts()):
                    $books->the_post();

                    // Hide topic pills here (already in topic context)
                    $show_topics = false;

                    get_template_part('templates/parts/audiobook-card');
                endwhile;

                wp_reset_postdata();
                ?>

            </div>

        </section>

    <?php endforeach; ?>

</section>