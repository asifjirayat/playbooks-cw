<?php
$plans = new WP_Query([
    'post_type'      => 'cw_pricing_plan',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

if ($plans->have_posts()) : ?>
    <section id="pricing" class="border-y border-ui-border py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4">

            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold leading-tight mb-4 text-ui-text">
                    One subscription. Every summary and Playbook unlocked.
                </h2>
                <p class="text-base text-ui-textSoft leading-relaxed">
                    Cancel any time. Keep the Playbooks you have downloaded.
                </p>
            </div>

            <!-- Pricing Grid -->
            <div class="grid md:grid-cols-2 gap-6 items-stretch">

                <?php while ($plans->have_posts()) : $plans->the_post();

                    $price        = get_field('price_amount');
                    $suffix       = get_field('price_suffix');
                    $desc         = get_field('description');
                    $features_raw = get_field('features');
                    $badge        = get_field('highlight_badge');
                    $btn_label    = get_field('button_label');
                    $btn_url      = get_field('button_url');

                    $features = array_filter(array_map('trim', explode("\n", trim($features_raw))));
                    $is_featured = !empty($badge);
                ?>

                    <!-- Card Wrapper -->
                    <?php if ($is_featured) : ?>
                        <div class="relative bg-gradient-to-br from-brand-primary to-blue-500 rounded-2xl p-[1px] shadow-lg">
                            <div class="relative bg-ui-bg rounded-2xl p-6 flex flex-col h-full">
                            <?php else : ?>
                                <div class="relative bg-ui-surface border border-ui-border rounded-2xl p-6 flex flex-col shadow-md">
                                <?php endif; ?>

                                <!-- Featured Badge -->
                                <?php if ($badge) : ?>
                                    <span class="absolute top-4 right-4 text-xs bg-brand-primary/10 text-brand-primary rounded-full px-2 py-0.5 tracking-wider border border-brand-primary/20">
                                        <?php echo esc_html($badge); ?>
                                    </span>
                                <?php endif; ?>

                                <!-- Plan Title -->
                                <p class="text-xs font-semibold text-ui-subtext uppercase tracking-widest mb-2">
                                    <?php the_title(); ?>
                                </p>

                                <!-- Price -->
                                <p class="text-4xl font-bold mb-2 text-ui-text leading-tight">
                                    <span>$</span><?php echo esc_html($price); ?>
                                    <span class="text-base font-medium text-ui-subtext">
                                        <?php echo esc_html($suffix); ?>
                                    </span>
                                </p>

                                <!-- Description -->
                                <p class="text-base text-ui-textSoft leading-relaxed mb-4">
                                    <?php echo esc_html($desc); ?>
                                </p>

                                <!-- Features -->
                                <ul class="space-y-3 text-base text-ui-subtext leading-relaxed mb-6">
                                    <?php foreach ($features as $feat) : ?>
                                        <li class="flex items-start gap-2">
                                            <span class="text-brand-primary mt-1">
                                                <i class="fa-solid fa-check"></i>
                                            </span>
                                            <span><?php echo esc_html($feat); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <!-- Button -->
                                <a href="<?php echo esc_url($btn_url); ?>"
                                    class="mt-auto inline-flex items-center justify-center rounded-full px-5 py-3 text-sm font-semibold transition
                                    <?php echo $is_featured
                                        ? 'bg-brand-primary text-white hover:bg-blue-600'
                                        : 'bg-ui-surface border border-ui-border text-ui-text hover:bg-ui-surfaceHover'; ?>">
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
wp_reset_postdata();
?>