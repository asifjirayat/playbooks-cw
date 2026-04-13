<?php

/**
 * Library Hero Section
 */

// Get summaries
$summaries = get_posts([
    'post_type'      => 'summary_of_day',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'ASC',
]);

// Reset array keys
$summaries = array_values($summaries);

if (empty($summaries)) return;

// Rotation
$total = count($summaries);
$index = date('z') % $total;

// Instant debug index
// $index = 88 % $total;

$today = $summaries[$index] ?? null;
if (!$today || empty($today->ID)) return;

$id = $today->ID;

// Fields (safe extraction)
$title_field = '';
$desc = '';

if (function_exists('get_field')) {
    $title_raw   = get_field('title', $id);
    $desc_raw    = get_field('short_description', $id);

    if (is_string($title_raw))   $title_field = $title_raw;
    if (is_string($desc_raw))    $desc = $desc_raw;
}

// Final values
$title   = $title_field ?: get_the_title($id);

// Safe URL handling
$link = '';

if (function_exists('get_field')) {
    $raw_link = get_field('post_link', $id);

    if (is_string($raw_link)) {
        $raw_link = trim($raw_link);

        if (!empty($raw_link) && filter_var($raw_link, FILTER_VALIDATE_URL)) {
            $link = $raw_link;
        }
    }
}

// Fallback
$link = $link ?: get_permalink($id);

?>

<section class="relative h-[480px] rounded-[2rem] overflow-hidden group max-w-7xl mx-auto mt-10">

    <!-- Static Background -->
    <img src="<?= esc_url(get_stylesheet_directory_uri() . '/assets/images/book-background.webp'); ?>"
        class="absolute inset-0 w-full h-full object-cover"
        alt="Library Hero">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>

    <!-- Content -->
    <div class="relative z-10 h-full flex flex-col justify-center px-10 md:px-16 max-w-2xl text-white">

        <!-- Eyebrow -->
        <span class="text-xs uppercase tracking-[0.3em] text-yellow-400 mb-4">
            Summary of the Day
        </span>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
            <?= esc_html($title); ?>
        </h1>

        <!-- Description -->
        <?php if (!empty($desc)): ?>
            <p class="text-base md:text-lg text-white/80 mb-8 leading-relaxed">
                <?= esc_html($desc); ?>
            </p>
        <?php endif; ?>

        <!-- CTA -->
        <div class="flex items-center gap-4">

            <a href="<?= esc_url($link); ?>"
                class="px-6 py-3 bg-brand-primary text-white font-semibold rounded-full flex items-center gap-2">
                <i class="fa-solid fa-play"></i>
                Listen Now
            </a>

            <a href="<?= esc_url(home_url('/audiobooks')); ?>"
                class="px-6 py-3 bg-white/10 text-white backdrop-blur-md rounded-full hover:bg-white/20 transition">
                Browse Library
            </a>

        </div>

    </div>

</section>