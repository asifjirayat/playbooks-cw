<?php

/**
 * Audiobook Card
 *
 * Optional variables before include:
 * @var bool $show_topics  Whether to show topic pills (default: true)
 * @var bool $show_author Whether to show author name (default: true)
 */

$show_topics = $show_topics ?? true;

// Core fields
$cover_url = get_field('featured_image_url');
$author    = get_field('book_author');
$duration  = get_field('duration');

// Topics
$topics = $show_topics
    ? get_the_terms(get_the_ID(), 'topics')
    : [];
?>

<a href="<?php the_permalink(); ?>" class="group block">

    <!-- Cover -->
    <div class="relative mb-4 overflow-hidden rounded-xl bg-ui-surface border border-ui-border">
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
    <h3 class="text-base font-semibold text-ui-text group-hover:text-brand-yellow transition">
        <?php the_title(); ?>
    </h3>

    <!-- Author -->
    <?php if ($author): ?>
        <p class="text-sm text-ui-subtext mt-1">
            <?php echo esc_html($author); ?>
        </p>
    <?php endif; ?>

    <!-- Topic Pills (optional) -->
    <?php if ($topics && !is_wp_error($topics)): ?>
        <div class="flex flex-wrap gap-1 mt-2">
            <?php foreach ($topics as $topic): ?>
                <span class="text-[10px] px-2 py-0.5 rounded-full
                             bg-ui-surface border border-ui-border text-ui-subtext">
                    <?php echo esc_html($topic->name); ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Duration -->
    <?php if ($duration): ?>
        <p class="text-sm text-ui-subtext mt-2">
            <?php echo esc_html($duration); ?>
        </p>
    <?php endif; ?>

</a>