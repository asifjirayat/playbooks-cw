<?php
$faqs = new WP_Query([
    'post_type'      => 'cw_faq',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

if ($faqs->have_posts()) : ?>
    <section class="max-w-4xl mx-auto px-4 py-16 md:py-24">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-extrabold mb-3 text-ui-text">Questions, answered.</h2>
            <p class="text-sm text-slate-300">A few things ambitious readers usually ask.</p>
        </div>

        <div class="space-y-4 text-sm">
            <?php while ($faqs->have_posts()) : $faqs->the_post();
                $answer = get_field('answer_text');
            ?>
                <details class="group bg-slate-900 border border-slate-800 rounded-xl p-5">
                    <summary class="flex items-center justify-between cursor-pointer list-none">
                        <span class="font-semibold text-slate-100"><?php the_title(); ?></span>
                        <span class="text-slate-500 group-open:rotate-90 transition-transform">
                            <i class="fa-solid fa-angle-right"></i>
                        </span>
                    </summary>
                    <p class="mt-3 text-slate-300 text-xs leading-relaxed"><?php echo esc_html($answer); ?></p>
                </details>
            <?php endwhile; ?>
        </div>
    </section>
<?php endif;
wp_reset_postdata(); ?>