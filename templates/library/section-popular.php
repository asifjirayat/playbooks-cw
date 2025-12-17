<section class="max-w-7xl mx-auto px-4 py-16">

    <h2 class="text-4xl font-bold text-ui-text mb-8 leading-tight">
        Popular Audiobooks
    </h2>

    <?php
    $popular = new WP_Query([
        'post_type'      => 'audiobook',
        'posts_per_page' => 12,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);

    if ($popular->have_posts()): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">

            <?php while ($popular->have_posts()): $popular->the_post(); ?>

                <a href="<?php the_permalink(); ?>" class="group block">

                    <!-- Thumbnail -->
                    <div class="relative mb-4 overflow-hidden rounded-xl bg-ui-surface border border-ui-border">
                        <img
                            src="<?php echo esc_url(get_field('featured_image_url')); ?>"
                            alt="<?php the_title_attribute(); ?>"
                            class="h-full w-full aspect-square object-contain bg-ui-surface rounded-xl transition-transform duration-300 group-hover:scale-105">
                    </div>

                    <!-- Title -->
                    <h3 class="text-base font-semibold text-ui-text group-hover:text-brand-yellow transition">
                        <?php the_title(); ?>
                    </h3>

                    <!-- Author -->
                    <?php if ($author = get_field('book_author')): ?>
                        <p class="text-sm text-ui-subtext mt-1">
                            <?php echo esc_html($author); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Topic Pills -->
                    <div class="flex flex-wrap gap-1 mt-2">
                        <?php
                        $topics = get_the_terms(get_the_ID(), 'topics');
                        if ($topics && !is_wp_error($topics)):
                            foreach ($topics as $topic): ?>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-ui-surface border border-ui-border text-ui-subtext">
                                    <?php echo esc_html($topic->name); ?>
                                </span>
                        <?php endforeach;
                        endif; ?>
                    </div>

                    <!-- Duration -->
                    <p class="text-sm text-ui-subtext mt-2">
                        <?php echo esc_html(get_field('duration')); ?>
                    </p>

                </a>

            <?php endwhile;
            wp_reset_postdata(); ?>

        </div>
    <?php else: ?>
        <p class="text-ui-subtext">No audiobooks found.</p>
    <?php endif; ?>

</section>