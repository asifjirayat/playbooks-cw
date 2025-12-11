<?php

/**
 * Single Audiobook Template
 */

get_header('audiobook');

// ACF fields
$cover_url        = get_field('featured_image_url') ?: '';
$audio_url        = get_field('audio_file_url') ?: '';
$workbook_url     = get_field('workbook_file_url') ?: '';
$short_summary    = get_field('short_summary') ?: '';
$long_summary     = get_field('long_summary') ?: '';
$narrator         = get_field('narrator') ?: 'Unknown Narrator';
$author           = get_field('author') ?: 'Unknown Author';
$duration         = get_field('duration') ?: '—';
$rank             = get_field('rank') ?: '';
$highlight_quote  = get_field('highlight_quote') ?: '';
$key_takeaways    = get_field('key_takeaways') ?: '';
$requires_premium = get_field('membership_required');

// Membership: premium = level 2 or 3
$premium_ids = array(2, 3);
$user_has_premium = function_exists('pmpro_hasMembershipLevel')
    && pmpro_hasMembershipLevel($premium_ids);

$is_locked = $requires_premium && !$user_has_premium;

// Taxonomy: Topics
$topics = wp_get_post_terms(get_the_ID(), 'topics');
?>

<main id="content" class="max-w-6xl mx-auto px-4 pb-24">

    <!-- HERO SECTION -->
    <section class="py-12">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            <!-- Left Column -->
            <div>

                <!-- Topic Chips -->
                <?php if ($topics): ?>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php foreach ($topics as $t): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-ui-surface/40 text-ui-text border border-ui-border">
                                <?= esc_html($t->name); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Title -->
                <h1 class="text-4xl lg:text-5xl font-bold text-ui-text mb-4">
                    <?php the_title(); ?>
                </h1>

                <!-- Author / Narrator -->
                <p class="text-ui-subtext font-medium text-sm mb-4">
                    By <span class="text-ui-text font-semibold"><?= esc_html($author); ?></span>
                    &middot; Narrated by <?= esc_html($narrator); ?>
                    &middot; <?= esc_html($duration); ?>
                </p>

                <!-- Short Summary -->
                <?php if ($short_summary): ?>
                    <p class="text-ui-subtext font-medium leading-relaxed mb-6">
                        <?= esc_html($short_summary); ?>
                    </p>
                <?php endif; ?>

                <!-- CTA Buttons -->
                <div class="flex items-center gap-4 mb-6">

                    <!-- Play Button -->
                    <button
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-semibold 
                               <?php if ($is_locked): ?>
                                   bg-slate-800 text-slate-500 cursor-not-allowed
                               <?php else: ?>
                                   bg-brand-primary text-white shadow-lg shadow-brand-primary/20 hover:brightness-110
                               <?php endif; ?>
                               transition"
                        id="playInlineTrigger">
                        <i class="fa-solid fa-play"></i>
                        <span>Listen</span>
                    </button>

                    <!-- Bookmark -->
                    <button class="w-11 h-11 rounded-full bg-ui-surface/30 border border-ui-border text-ui-text 
                                   hover:bg-ui-surface/50 transition" id="bookmarkBtnTop">
                        <i class="fa-regular fa-bookmark"></i>
                    </button>

                    <!-- Workbook Download -->
                    <?php if (!$is_locked && $workbook_url): ?>
                        <a href="<?= esc_url($workbook_url); ?>"
                            class="w-11 h-11 rounded-full bg-brand.yellow text-black flex items-center justify-center 
                                  font-semibold hover:brightness-90 transition"
                            download title="Download Workbook">
                            <i class="fa-solid fa-file-arrow-down"></i>
                        </a>
                    <?php else: ?>
                        <button class="w-11 h-11 rounded-full bg-slate-800 text-slate-500 border border-ui-border cursor-not-allowed"
                            title="Premium required">
                            <i class="fa-solid fa-lock"></i>
                        </button>
                    <?php endif; ?>
                </div>

                <?php if ($is_locked): ?>
                    <div class="text-sm text-red-400 font-medium mb-4">
                        Premium membership required to access full audio + workbook.
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right Column: Cover -->
            <div class="flex justify-center md:justify-end relative">
                <div class="relative inline-block">
                    <?php if ($rank): ?>
                        <span class="absolute top-2 left-2 -translate-x-1/4 -translate-y-1/4 
                         bg-brand-yellow text-black text-[12px] font-semibold rounded-full px-3 py-1 shadow-lg z-20
                         sm:top-3 sm:left-3 sm:-translate-x-1/2 sm:-translate-y-1/2">
                            #<?= esc_html($rank); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($cover_url): ?>
                        <img src="<?= esc_url($cover_url); ?>"
                            alt="<?php the_title_attribute(); ?> cover"
                            class="w-56 md:w-72 rounded-xl shadow-xl border border-ui-border object-contain block">
                    <?php else : ?>
                        <div class="w-56 md:w-72 h-80 bg-ui-surface rounded-xl border border-ui-border flex items-center justify-center text-ui-subtext">
                            No cover
                        </div>
                    <?php endif; ?>
                </div>
            </div>


        </div>
    </section>

    <!-- INLINE PLAYER (moved to sit under the cover) -->
    <section class="my-8 md:my-12">
        <?php
        // Pass audio URL + lock state to player partial
        set_query_var('cw_audio_url', $audio_url);
        set_query_var('cw_locked', $is_locked);
        get_template_part('templates/parts/player');
        ?>
    </section>


    <!-- KEY TAKEAWAYS -->
    <?php if ($key_takeaways): ?>
        <section class="bg-ui-surface/20 border border-ui-border rounded-xl p-6 mb-10">
            <h2 class="text-xl font-bold text-ui-text mb-4">Key Takeaways</h2>

            <ul class="space-y-3 text-ui-subtext font-medium">
                <?php
                $lines = preg_split('/\r\n|\r|\n/', $key_takeaways);
                foreach ($lines as $line):
                    if (trim($line)):
                ?>
                        <li class="flex gap-3">
                            <span class="text-emerald-400 mt-1"><i class="fa-solid fa-check"></i></span>
                            <span><?= esc_html($line); ?></span>
                        </li>
                <?php
                    endif;
                endforeach;
                ?>
            </ul>
        </section>
    <?php endif; ?>

    <!-- HIGHLIGHT QUOTE -->
    <?php if ($highlight_quote): ?>
        <section class="my-12">
            <blockquote class="border-l-4 border-brand.yellow pl-6 py-2 text-xl italic text-ui-text">
                “<?= esc_html($highlight_quote); ?>”
            </blockquote>
        </section>
    <?php endif; ?>

    <!-- LONG SUMMARY -->
    <article class="prose prose-invert max-w-none leading-relaxed text-ui-subtext">
        <?= wp_kses_post($long_summary); ?>
    </article>

    <!-- WORKBOOK CTA -->
    <section class="mt-16">
        <?php if ($is_locked): ?>
            <div class="bg-slate-900 border border-ui-border p-6 rounded-xl text-center">
                <p class="text-ui-subtext mb-4">Workbook available for premium members.</p>
                <a href="/membership"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-brand-primary text-white font-semibold hover:brightness-110">
                    Get Premium Access
                </a>
            </div>
        <?php elseif ($workbook_url): ?>
            <a href="<?= esc_url($workbook_url); ?>"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-brand-yellow text-black font-semibold hover:brightness-95 shadow-md transition"
                download>
                Download Workbook <i class="fa-solid fa-download"></i>
            </a>
        <?php endif; ?>
    </section>

</main>

<?php get_footer(); ?>