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
$amazon_link         = get_field('amazon_link') ?: '';
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

// Topics
$topics = wp_get_post_terms(get_the_ID(), 'topics');
$primary_topic = !empty($topics) && !is_wp_error($topics) ? $topics[0] : null;

// Concepts
$concepts = array_filter(array_map('trim', preg_split('/,|\r\n|\r|\n/', $concepts_raw)));
?>

<main id="content" class="max-w-3xl mx-auto px-6 py-24">

    <!-- HERO -->
    <section class="mb-16">
        <div class="flex flex-col md:flex-row gap-8 items-start">

            <!-- COVER -->
            <div class="relative w-48 shrink-0 aspect-square rounded-xl overflow-hidden shadow-lg">

                <?php if ($book_cover_url): ?>
                    <img src="<?= esc_url($book_cover_url); ?>"
                        class="w-full h-full object-cover"
                        alt="<?php the_title_attribute(); ?>">
                <?php endif; ?>

                <?php if ($rank): ?>
                    <div class="absolute top-2 left-2 bg-yellow-400 text-black text-xs font-bold px-2.5 py-1 rounded-md shadow">
                        #<?= esc_html($rank); ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- CONTENT -->
            <div class="flex-1">

                <?php if ($primary_topic): ?>
                    <span class="text-xs font-semibold tracking-[0.2em] uppercase text-brand-primary block mb-3">
                        <?= esc_html($primary_topic->name); ?>
                    </span>
                <?php endif; ?>

                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                    <?php the_title(); ?>
                </h1>

                <p class="text-lg text-ui-subtext font-medium mb-6">
                    <?= esc_html($author); ?>
                </p>

                <!-- META -->
                <div class="flex flex-wrap gap-4 text-sm text-ui-subtext mb-6">
                    <?php if ($listening_time): ?>
                        <div class="flex items-center gap-1">
                            <i class="fa-regular fa-clock"></i>
                            <span><?= esc_html($listening_time); ?> min read</span>
                        </div>
                    <?php endif; ?>

                    <div class="flex items-center gap-1">
                        <i class="fa-regular fa-headphones"></i>
                        <span><?= esc_html($listening_time); ?> min audio</span>
                    </div>
                </div>

                <!-- CONCEPT TAGS -->
                <?php if ($concepts): ?>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <?php foreach ($concepts as $concept): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-ui-surface border border-ui-border text-ui-subtext">
                                <?= esc_html(ucwords($concept)); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($is_locked): ?>
                    <p class="text-sm text-ui-subtext mt-4">
                        Premium membership required to access full audio + workbook.
                    </p>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <!-- PLAYER -->
    <section class="mb-16">
        <?php
        set_query_var('cw_audio_url', $audio_summary_url);
        set_query_var('cw_locked', $is_locked);
        get_template_part('templates/parts/player');
        ?>
    </section>

    <!-- VALUE -->
    <?php if ($whats_in_it_for_you): ?>
        <section class="mb-12">
            <h2 class="text-3xl text-ui-textSoft font-bold mb-6">The Core Lesson</h2>
            <p class="text-ui-textSoft leading-relaxed">
                <?= esc_html($whats_in_it_for_you); ?>
            </p>
        </section>
    <?php endif; ?>

    <!-- QUOTE -->
    <?php if ($best_quote): ?>
        <blockquote class="border-l-4 border-brand-primary pl-8 py-2 my-12">

            <p class="text-2xl font-semibold leading-snug text-ui-text">
                “<?= esc_html($best_quote); ?>”
            </p>

            <?php if ($author): ?>
                <cite class="block mt-4 text-ui-subtext font-medium not-italic">
                    — <?= esc_html($author); ?>
                </cite>
            <?php endif; ?>

        </blockquote>
    <?php endif; ?>

    <!-- TAKEAWAYS -->
    <?php if ($key_takeaways): ?>
        <section class="bg-ui-bgYellow p-8 rounded-2xl mb-12">
            <h2 class="text-xl text-ui-textTertiary font-bold mb-6 flex items-center gap-2">
                <i class="fa-solid fa-lightbulb"></i>
                <span>Key Takeaways</span>
            </h2>

            <ul class="space-y-4">
                <?php foreach (preg_split('/\r\n|\r|\n/', $key_takeaways) as $line):
                    if (trim($line)): ?>
                        <li class="flex items-start gap-3 font-regular">
                            <span class="text-ui-bgYellowIcon mt-[2px]">
                                <i class="fa-solid fa-circle-check text-xl"></i>
                            </span>
                            <span><?= esc_html($line); ?></span>
                        </li>
                <?php endif;
                endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <!-- SUMMARY -->
    <?php if ($long_summary): ?>
        <article class="prose max-w-none text-ui-textSoft mb-12">
            <h2 class="text-3xl font-bold mb-6">The Executive Summary</h2>
            <?= wp_kses_post($long_summary); ?>
        </article>
    <?php endif; ?>

    <!-- CTA -->
    <section class="mt-16">
        <div class="bg-yellow-400 text-slate-900 p-8 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg">

            <div>
                <h3 class="text-xl font-bold mb-1">Deepen your understanding</h3>
                <p class="text-slate-800">Download the <span class="font-bold">"<?php the_title(); ?>"</span> workbook.</p>
            </div>

            <?php if (!$is_locked && $playbook_url): ?>
                <a href="<?= esc_url($playbook_url); ?>"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-full font-semibold no-underline hover:opacity-90 transition"
                    download>
                    <i class="fa-solid fa-download"></i>
                    Download
                </a>
            <?php else: ?>
                <a href="/membership"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-full font-semibold no-underline hover:opacity-90 transition">
                    <i class="fa-solid fa-lock"></i>
                    Get Access
                </a>
            <?php endif; ?>

        </div>
    </section>

    <!-- ORIGINAL BOOK CTA -->
    <section class="mt-12">
        <div class="bg-ui-bgYellow border border-ui-border rounded-2xl p-8 flex flex-col md:flex-row items-center gap-6">

            <!-- Content -->
            <div class="flex-1 text-center md:text-left">

                <h3 class="text-2xl font-bold text-ui-textTertiary mb-3 flex items-center justify-center md:justify-start gap-2">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Dive Deeper into the Original Work</span>
                </h3>

                <p class="text-ui-textSoft leading-relaxed mb-5 max-w-xl mx-auto md:mx-0">
                    A summary can only scratch the surface. To truly grasp the depth of research, real-world examples, and the author's unique voice, there is no substitute for the full book.
                </p>

                <p class="text-ui-subtext text-sm mb-6">
                    If these insights resonated with you, we recommend exploring the original text.
                </p>

                <a href="<?= esc_url($amazon_link ?: '#'); ?>" target="_blank"
                    class="inline-flex items-center gap-2 bg-brand-yellow text-black px-6 py-3 rounded-full font-semibold no-underline hover:opacity-90 transition shadow">
                    <i class="fa-brands fa-amazon mt-1"></i>
                    Get the Full Book on Amazon
                </a>

            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>