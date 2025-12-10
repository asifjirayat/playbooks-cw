<?php
$plans = new WP_Query([
    'post_type'      => 'cw_pricing_plan',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

if ($plans->have_posts()) : ?>
    <section id="pricing" class="bg-slate-900 border-y border-slate-900 py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-extrabold mb-3 text-ui-text">One subscription. Every summary and Playbook unlocked.</h2>
                <p class="text-sm text-slate-300">Cancel any time. Keep the Playbooks you have downloaded.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 items-stretch">

                <?php while ($plans->have_posts()) : $plans->the_post();

                    $price        = get_field('price_amount');
                    $suffix       = get_field('price_suffix');
                    $desc         = get_field('description');
                    $features_raw = get_field('features');
                    $badge        = get_field('highlight_badge');
                    $btn_label    = get_field('button_label');
                    $btn_url      = get_field('button_url');

                    $features = explode("\n", trim($features_raw));
                ?>

                    <?php
                    $is_featured = !empty($badge);

                    if ($is_featured) : ?>
                        <div class="relative bg-gradient-to-br from-brand-primary to-blue-500 rounded-2xl p-[1px] shadow-xl">
                            <div class="relative bg-slate-950 rounded-2xl p-6 flex flex-col h-full">
                            <?php else : ?>
                                <div class="relative bg-slate-950 border border-slate-800 rounded-2xl p-6 flex flex-col shadow-xl">
                                <?php endif; ?>

                                <?php if ($badge) : ?>
                                    <span class="absolute top-4 right-4 text-[11px] bg-emerald-400/20 text-emerald-300 rounded-full px-2 py-0.5 shadow">
                                        <?php echo esc_html($badge); ?>
                                    </span>
                                <?php endif; ?>

                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-[0.18em] mb-1">
                                    <?php the_title(); ?>
                                </p>

                                <p class="text-3xl font-extrabold mb-1 text-ui-text">
                                    <?php echo esc_html($price); ?>
                                    <span class="text-base font-medium text-slate-400"><?php echo esc_html($suffix); ?></span>
                                </p>

                                <p class="text-xs text-slate-400 mb-4"><?php echo esc_html($desc); ?></p>

                                <ul class="space-y-2 text-xs text-slate-200 mb-6">
                                    <?php foreach ($features as $feat) : ?>
                                        <li><?php echo esc_html($feat); ?></li>
                                    <?php endforeach; ?>
                                </ul>

                                <a href="<?php echo esc_url($btn_url); ?>"
                                    class="mt-auto inline-flex items-center justify-center rounded-full <?php echo $is_featured ? 'bg-white text-slate-900' : 'bg-slate-200 text-slate-900'; ?> font-semibold text-sm px-4 py-2.5 hover:bg-slate-100 transition">
                                    <?php echo esc_html($btn_label); ?>
                                </a>

                                <?php if ($is_featured) : ?>
                                </div>
                            </div>
                        <?php else : ?>
                        </div>
                    <?php endif; ?>


                <?php endwhile; ?>

            </div>
        </div>
    </section>
<?php endif;
wp_reset_postdata(); ?>