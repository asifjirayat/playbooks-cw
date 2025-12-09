<section class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-ui-text mb-6">Editor's Picks</h2>

    <?php
    $editors = new WP_Query([
        'post_type'      => 'audiobook',
        'posts_per_page' => 12,
        'orderby'        => 'title',
        'order'          => 'DESC',
    ]);

    if ($editors->have_posts()): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
            <?php while ($editors->have_posts()): $editors->the_post(); ?>

                <a href="<?php the_permalink(); ?>" class="group block">

                    <div class="relative mb-3 overflow-hidden rounded-xl bg-ui-surface border border-ui-border">
                        <img
                            src="<?php echo esc_url(get_field('featured_image_url')); ?>"
                            alt="<?php the_title_attribute(); ?>"
                            class="h-full w-full aspect-square object-contain bg-ui-surface rounded-xl transition-transform duration-300 group-hover:scale-105">
                    </div>

                    <h3 class="text-sm font-semibold text-ui-text group-hover:text-brand-yellow transition">
                        <?php the_title(); ?>
                    </h3>

                    <?php if ($author = get_field('author')): ?>
                        <p class="text-xs text-ui-subtext mt-0.5">
                            <?php echo esc_html($author); ?>
                        </p>
                    <?php endif; ?>

                    <div class="flex flex-wrap gap-1 mt-1">
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

                    <p class="text-xs text-ui-subtext mt-1">
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