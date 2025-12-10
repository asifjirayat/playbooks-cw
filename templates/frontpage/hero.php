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

<section class="relative py-20 lg:py-28 bg-gradient-to-b from-brand-darker to-ui-bg overflow-hidden">

    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-16 items-center">

        <!-- LEFT COLUMN -->
        <div>

            <?php if ($eyebrow): ?>
                <p class="text-xs font-semibold tracking-[0.24em] uppercase text-slate-400 mb-3">
                    <?= esc_html($eyebrow); ?>
                </p>
            <?php endif; ?>

            <?php if ($headline): ?>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black leading-tight tracking-tight mb-4 text-ui-text">
                    <?= esc_html($headline); ?><br>
                    <?php if ($highlight): ?>
                        <span class="text-brand-primary"><?= esc_html($highlight); ?></span>
                    <?php endif; ?>
                </h1>
            <?php endif; ?>

            <?php if ($subtext): ?>
                <p class="text-base sm:text-lg text-slate-300 mb-5 max-w-xl">
                    <?= esc_html($subtext); ?>
                </p>
            <?php endif; ?>


            <!-- BULLETS -->
            <ul class="space-y-2 text-sm text-slate-300 mb-6">
                <?php if ($bullet1): ?>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-400 mt-0.5"><i class="fa-solid fa-check"></i></span>
                        <span><?= esc_html($bullet1); ?></span>
                    </li>
                <?php endif; ?>

                <?php if ($bullet2): ?>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-400 mt-0.5"><i class="fa-solid fa-check"></i></span>
                        <span><?= esc_html($bullet2); ?></span>
                    </li>
                <?php endif; ?>

                <?php if ($bullet3): ?>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-400 mt-0.5"><i class="fa-solid fa-check"></i></span>
                        <span><?= esc_html($bullet3); ?></span>
                    </li>
                <?php endif; ?>
            </ul>


            <!-- CTA BUTTONS -->
            <div class="flex flex-wrap items-center gap-3">

                <?php if ($cta1_label && $cta1_url): ?>
                    <a href="<?= esc_url($cta1_url); ?>"
                        class="inline-flex items-center gap-2 rounded-full bg-brand-primary px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-primary/20 hover:text-white hover:shadow-brand-primary/50 transition">
                        <?= esc_html($cta1_label); ?>
                        <i class="fa-solid fa-arrow-down text-xs opacity-80"></i>
                    </a>
                <?php endif; ?>

                <?php if ($cta2_label): ?>
                    <a href="<?= esc_url($cta2_url ?: '#'); ?>"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-600 px-5 py-3 text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-800 transition">
                        <?= esc_html($cta2_label); ?>
                        <i class="fa-solid fa-play text-[10px]"></i>
                    </a>
                <?php endif; ?>

            </div>

            <?php if ($footnote): ?>
                <p class="mt-3 text-xs text-slate-500">
                    <?= esc_html($footnote); ?>
                </p>
            <?php endif; ?>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="flex justify-center md:justify-end">
            <div class="relative w-auto md:max-w-md">

                <?php if ($badge): ?>
                    <div class="absolute -top-4 -left-4 bg-emerald-500 text-emerald-950 text-[11px] font-bold rounded-full px-3 py-1 shadow-lg">
                        <?= esc_html($badge); ?>
                    </div>
                <?php endif; ?>

                <?php if ($hero_img): ?>
                    <img src="<?= esc_url($hero_img['url']); ?>"
                        alt="<?= esc_attr($hero_img['alt']); ?>"
                        class="w-full h-auto object-cover rounded-xl shadow-xl shadow-black/50 border border-slate-700">
                <?php endif; ?>

                <div class="absolute -bottom-4 -right-3 bg-slate-900/95 border border-slate-700 rounded-xl px-4 py-3 text-xs shadow-xl max-w-[210px]">
                    <?php if ($tooltip_title): ?>
                        <p class="font-semibold text-slate-100 mb-1"><?= esc_html($tooltip_title); ?></p>
                    <?php endif; ?>

                    <ul class="space-y-0.5 text-slate-400">
                        <?php if ($tip1): ?><li>• <?= esc_html($tip1); ?></li><?php endif; ?>
                        <?php if ($tip2): ?><li>• <?= esc_html($tip2); ?></li><?php endif; ?>
                        <?php if ($tip3): ?><li>• <?= esc_html($tip3); ?></li><?php endif; ?>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</section>