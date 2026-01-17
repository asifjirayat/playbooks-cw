<?php

/**
 * Audiobook Card
 *
 * Optional variables before include:
 * @var bool $show_topics
 * @var bool $show_author
 */

$show_author = $show_author ?? true;

// Core fields
$cover_url = get_field('book_cover_url');
$author    = get_field('book_author');
$duration  = get_field('listening_time');
$concepts_raw = get_field('concepts');

// Normalize concepts → pills
$concepts = $concepts_raw
    ? array_filter(array_map('trim', preg_split('/,|\r\n|\r|\n/', $concepts_raw)))
    : [];
?>

<article class="group block relative">

    <!-- STRETCHED LINK (card click target) -->
    <a href="<?php the_permalink(); ?>" class="absolute inset-0 z-10" aria-label="<?php the_title_attribute(); ?>"></a>

    <!-- Cover -->
    <div class="relative mb-4 overflow-hidden rounded-xl bg-ui-surface border border-ui-border z-0">
        <?php if ($cover_url): ?>
            <img
                src="<?php echo esc_url($cover_url); ?>"
                alt="<?php the_title_attribute(); ?>"
                class="w-full aspect-square object-contain bg-ui-surface rounded-xl
                       transition-transform duration-300 group-hover:scale-105">
        <?php else: ?>
            <div class="aspect-square flex items-center justify-center text-ui-subtext text-sm">
                No cover
            </div>
        <?php endif; ?>
    </div>

    <!-- Title -->
    <h3 class="text-base font-semibold text-ui-text group-hover:text-brand-yellow transition relative z-20">
        <?php the_title(); ?>
    </h3>

    <!-- Author -->
    <?php if ($show_author && $author): ?>
        <p class="text-sm text-ui-subtext mt-1 relative z-20">
            <?php echo esc_html($author); ?>
        </p>
    <?php endif; ?>

    <!-- Duration -->
    <?php if ($duration): ?>
        <p class="text-sm text-ui-subtext mt-2 relative z-20">
            <?php echo esc_html($duration); ?> <span>Minutes</span>
        </p>
    <?php endif; ?>

</article>