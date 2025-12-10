<?php
$testimonials = new WP_Query([
    'post_type'      => 'cw_testimonial',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

if ($testimonials->have_posts()) : ?>
    <section class="max-w-6xl mx-auto px-4 py-14 md:py-18">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-extrabold mb-3 text-ui-text">Trusted by people who measure their lives in outcomes, not pages read.</h2>
            <p class="text-sm text-slate-300">Founders, operators, and ambitious professionals across domains.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-5 text-sm">
            <?php while ($testimonials->have_posts()) : $testimonials->the_post();
                $quote = get_field('quote_text');
                $role  = get_field('author_role');
            ?>
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                    <p class="text-slate-200 italic mb-4">
                        <?php echo esc_html($quote); ?>
                    </p>
                    <?php if ($role) : ?>
                        <p class="text-xs text-slate-500"><?php echo esc_html($role); ?></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
<?php endif;
wp_reset_postdata(); ?>