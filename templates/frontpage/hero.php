<?php
// HERO FIELDS
$eyebrow        = get_field('hero_eyebrow');
$headline       = get_field('hero_headline');
$highlight      = get_field('hero_highlight');
$subtext        = get_field('hero_subtext');

$bullet1        = get_field('hero_bullet_1');
$bullet2        = get_field('hero_bullet_2');
$bullet3        = get_field('hero_bullet_3');

$cta1_label     = get_field('hero_primary_label');
$cta1_url       = get_field('hero_primary_url');

$cta2_label     = get_field('hero_secondary_label');
$cta2_url       = get_field('hero_secondary_url');

$footnote       = get_field('hero_footnote');

$hero_img       = get_field('hero_image');
$badge          = get_field('hero_badge');

$tooltip_title  = get_field('hero_tooltip_title');
$tip1           = get_field('hero_tooltip_1');
$tip2           = get_field('hero_tooltip_2');
$tip3           = get_field('hero_tooltip_3');

$tooltip_img    = get_field('hero_tooltip_image'); // optional
?>

<section class="relative py-16 lg:py-20 bg-ui-bg overflow-hidden border-b border-ui-border">

    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-16 items-center">

        <!-- LEFT COLUMN -->
        <div>

            <?php if ($eyebrow): ?>
                <p class="text-xs font-medium tracking-widest uppercase text-ui-subtext mb-3">
                    <?= esc_html($eyebrow); ?>
                </p>
            <?php endif; ?>

            <?php if ($headline): ?>
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold leading-tight text-ui-text mb-6">
                    <?= esc_html($headline); ?>
                    <?php if ($highlight): ?>
                        <br><span class="text-brand-primary"><?= esc_html($highlight); ?></span>
                    <?php endif; ?>
                </h1>
            <?php endif; ?>

            <?php if ($subtext): ?>
                <p class="text-base sm:text-lg leading-relaxed text-ui-textSoft mb-6 max-w-xl">
                    <?= esc_html($subtext); ?>
                </p>
            <?php endif; ?>


            <!-- BULLETS -->
            <ul class="space-y-3 text-sm text-ui-subtext mb-8">
                <?php foreach ([$bullet1, $bullet2, $bullet3] as $bullet): ?>
                    <?php if ($bullet): ?>
                        <li class="flex items-start gap-3">
                            <span class="text-brand-primary mt-0.5"><i class="fa-solid fa-check"></i></span>
                            <span><?= esc_html($bullet); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>


            <!-- CTA BUTTONS -->
            <div class="flex flex-wrap items-center gap-4">

                <?php if ($cta1_label && $cta1_url): ?>
                    <a href="<?= esc_url($cta1_url); ?>"
                        class="inline-flex items-center gap-2 rounded-full bg-brand-primary px-6 py-3 text-sm font-semibold text-white shadow-md hover:shadow-lg transition">
                        <?= esc_html($cta1_label); ?>
                        <i class="fa-solid fa-arrow-down text-xs opacity-80"></i>
                    </a>
                <?php endif; ?>

                <?php if ($cta2_label): ?>
                    <a href="<?= esc_url($cta2_url ?: '#'); ?>"
                        class="inline-flex items-center gap-2 rounded-full border border-ui-border px-6 py-3 text-sm font-semibold text-ui-text hover:bg-ui-surface transition">
                        <?= esc_html($cta2_label); ?>
                        <i class="fa-solid fa-play text-[10px] opacity-80"></i>
                    </a>
                <?php endif; ?>

            </div>

            <?php if ($footnote): ?>
                <p class="mt-4 text-xs text-ui-muted leading-normal">
                    <?= esc_html($footnote); ?>
                </p>
            <?php endif; ?>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="flex justify-center md:justify-end">
            <div class="relative w-auto md:max-w-md">

                <?php if ($badge): ?>
                    <div class="absolute -top-4 -left-4 bg-ui-bg text-brand-primary text-[11px] font-semibold rounded-full px-3 py-1 border border-brand-primary/20">
                        <?= esc_html($badge); ?>
                    </div>
                <?php endif; ?>

                <?php if ($hero_img): ?>
                    <img src="<?= esc_url($hero_img['url']); ?>"
                        alt="<?= esc_attr($hero_img['alt']); ?>"
                        class="w-full h-auto object-cover rounded-xl shadow-lg border border-ui-border">
                <?php endif; ?>

                <div class="absolute -bottom-4 -right-3 bg-ui-surface border border-ui-border rounded-xl px-4 py-3 text-xs shadow-md max-w-[210px]">
                    <?php if ($tooltip_title): ?>
                        <p class="font-semibold text-ui-text mb-2"><?= esc_html($tooltip_title); ?></p>
                    <?php endif; ?>

                    <ul class="space-y-1 text-ui-subtext leading-normal">
                        <?php if ($tip1): ?><li>• <?= esc_html($tip1); ?></li><?php endif; ?>
                        <?php if ($tip2): ?><li>• <?= esc_html($tip2); ?></li><?php endif; ?>
                        <?php if ($tip3): ?><li>• <?= esc_html($tip3); ?></li><?php endif; ?>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</section>