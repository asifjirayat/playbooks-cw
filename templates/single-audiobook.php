<?php

/**
 * Single Audiobook Template
 */

get_header('audiobook');

// ACF fields
$book_cover_url      = get_field('book_cover_url') ?: '';
$audio_summary_url   = get_field('audio_summary_url') ?: '';
$playbook_url        = get_field('playbook_url') ?: '';
$whats_in_it_for_you = get_field('whats_in_it_for_you') ?: '';
$long_summary        = get_field('long_summary') ?: '';
$author              = get_field('book_author') ?: 'Unknown Author';
$listening_time      = get_field('listening_time') ?: '';
$rank                = get_field('rank') ?: '';
$best_quote          = get_field('best_quote') ?: '';
$key_takeaways       = get_field('key_takeaways') ?: '';
$concepts_raw        = get_field('concepts') ?: '';
$requires_premium    = get_field('membership_required');

// Membership
$premium_ids = [2, 3];
$user_has_premium = function_exists('pmpro_hasMembershipLevel')
    && pmpro_hasMembershipLevel($premium_ids);

$is_locked = $requires_premium && !$user_has_premium;

// Topics (taxonomy)
$topics = wp_get_post_terms(get_the_ID(), 'topics');
$primary_topic = !empty($topics) && !is_wp_error($topics) ? $topics[0] : null;

// Concepts → split into pills (comma or line separated)
$concepts = array_filter(array_map('trim', preg_split('/,|\r\n|\r|\n/', $concepts_raw)));
?>

<main id="content" class="max-w-7xl mx-auto px-4 pb-24">

    <!-- HERO -->
    <section class="py-12">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            <!-- LEFT COLUMN -->
            <div>

                <!-- Concepts as pills (above title) -->
                <?php if ($concepts): ?>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php foreach ($concepts as $concept): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                         bg-ui-surface/40 text-ui-text border border-ui-border">
                                <?= esc_html(ucwords($concept)); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Title -->
                <h1 class="text-4xl lg:text-5xl font-bold text-ui-text mb-4">
                    <?php the_title(); ?>
                </h1>

                <!-- Author + Listening Time -->
                <p class="text-ui-subtext font-medium text-sm mb-4">
                    By <span class="text-ui-text font-semibold"><?= esc_html($author); ?></span>
                    <?php if ($listening_time): ?>
                        · Listening Time <?= esc_html($listening_time); ?> Minutes
                    <?php endif; ?>
                </p>

                <!-- What's in it for you -->
                <?php if ($whats_in_it_for_you): ?>
                    <p class="text-ui-subtext font-medium leading-relaxed mb-6">
                        <?= esc_html($whats_in_it_for_you); ?>
                    </p>
                <?php endif; ?>

                <!-- CTA Buttons -->
                <div class="flex items-center gap-4 mb-6">

                    <button
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-semibold 
                               <?= $is_locked
                                    ? 'bg-slate-800 text-slate-500 cursor-not-allowed'
                                    : 'bg-brand-primary text-white shadow-lg shadow-brand-primary/20 hover:brightness-110'; ?>
                               transition"
                        id="playInlineTrigger">
                        <i class="fa-solid fa-play"></i>
                        <span>Listen</span>
                    </button>

                    <button class="w-11 h-11 rounded-full bg-ui-surface/30 border border-ui-border text-ui-text 
                                   hover:bg-ui-surface/50 transition" id="bookmarkBtnTop">
                        <i class="fa-regular fa-bookmark"></i>
                    </button>

                    <?php if (!$is_locked && $playbook_url): ?>
                        <a href="<?= esc_url($playbook_url); ?>"
                            class="w-11 h-11 rounded-full bg-brand-yellow text-black flex items-center justify-center 
                                  font-semibold hover:brightness-90 transition"
                            download title="Download Workbook">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    <?php else: ?>
                        <button class="w-11 h-11 rounded-full bg-slate-800 text-slate-500 border border-ui-border cursor-not-allowed">
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

            <!-- RIGHT COLUMN -->
            <div class="flex justify-center md:justify-end relative">
                <div class="relative inline-block text-center">

                    <?php if ($rank): ?>
                        <span class="absolute top-2 left-2 -translate-x-1/4 -translate-y-1/4 
                                     bg-brand-yellow text-black text-[12px] font-semibold rounded-full px-3 py-1 shadow-lg z-20">
                            Rank #<?= esc_html($rank); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($book_cover_url): ?>
                        <img src="<?= esc_url($book_cover_url); ?>"
                            alt="<?php the_title_attribute(); ?> cover"
                            class="w-56 md:w-72 rounded-xl shadow-xl border border-ui-border object-contain block mx-auto">
                    <?php else: ?>
                        <div class="w-56 md:w-72 h-80 bg-ui-surface rounded-xl border border-ui-border flex items-center justify-center text-ui-subtext">
                            No cover
                        </div>
                    <?php endif; ?>

                    <!-- Primary Topic (clickable, under image) -->
                    <?php if ($primary_topic): ?>
                        <a href="<?= esc_url(get_term_link($primary_topic)); ?>"
                            class="inline-block mt-3 text-xs font-semibold text-brand-yellow hover:underline">
                            <?= esc_html($primary_topic->name); ?>
                        </a>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </section>

    <!-- INLINE PLAYER -->
    <section class="my-8 md:my-12">
        <?php
        set_query_var('cw_audio_url', $audio_summary_url);
        set_query_var('cw_locked', $is_locked);
        get_template_part('templates/parts/player');
        ?>
    </section>

    <!-- KEY TAKEAWAYS -->
    <?php if ($key_takeaways): ?>
        <section class="bg-ui-surface/20 border border-ui-border rounded-xl p-6 mb-10">
            <h2 class="text-xl font-bold text-ui-text mb-4">Key Takeaways</h2>

            <ul class="space-y-3 text-ui-subtext font-medium">
                <?php foreach (preg_split('/\r\n|\r|\n/', $key_takeaways) as $line):
                    if (trim($line)): ?>
                        <li class="flex gap-3">
                            <span class="text-emerald-400 mt-1"><i class="fa-solid fa-check"></i></span>
                            <span><?= esc_html($line); ?></span>
                        </li>
                <?php endif;
                endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <!-- HIGHLIGHT QUOTE -->
    <?php if ($best_quote): ?>
        <section class="my-12">
            <blockquote class="border-l-4 border-brand-yellow pl-6 py-2 text-xl italic text-ui-text">
                “<?= esc_html($best_quote); ?>”
            </blockquote>
        </section>
    <?php endif; ?>

    <!-- LONG SUMMARY -->
    <?php if ($long_summary): ?>
        <article class="prose prose-invert max-w-none leading-relaxed text-ui-subtext">
            <?= wp_kses_post($long_summary); ?>
        </article>
    <?php endif; ?>

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
        <?php elseif ($playbook_url): ?>
            <a href="<?= esc_url($playbook_url); ?>"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-brand-yellow text-black font-semibold hover:brightness-95 shadow-md transition"
                download>
                Download Workbook <i class="fa-solid fa-download"></i>
            </a>
        <?php endif; ?>
    </section>

</main>

<?php get_footer(); ?>