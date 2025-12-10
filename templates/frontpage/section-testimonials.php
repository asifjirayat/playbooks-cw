<?php
$testimonials = new WP_Query([
    'post_type'      => 'cw_testimonial',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

if ($testimonials->have_posts()) : ?>
    <section class="max-w-6xl mx-auto px-4 py-16 md:py-24">

        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold leading-tight mb-4 text-ui-text">
                Trusted by people who measure their lives in outcomes, not pages read.
            </h2>
            <p class="text-base text-ui-subtext leading-relaxed">
                Founders, operators, and ambitious professionals across domains.
            </p>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid md:grid-cols-3 gap-6 text-base">

            <?php while ($testimonials->have_posts()) : $testimonials->the_post();
                $quote = get_field('quote_text');
                $role  = get_field('author_role');
            ?>
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between shadow-lg">

                    <p class="text-ui-text italic leading-relaxed mb-4">
                        <?php echo esc_html($quote); ?>
                    </p>

                    <?php if ($role) : ?>
                        <p class="text-xs text-ui-subtext leading-normal">
                            <?php echo esc_html($role); ?>
                        </p>
                    <?php endif; ?>

                </div>
            <?php endwhile; ?>

        </div>

    </section>
<?php endif;
wp_reset_postdata(); ?>