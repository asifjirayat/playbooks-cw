<?php

/**
 * Topic Sidebar – Top Authors
 */

$term = get_queried_object();

if (!$term || is_wp_error($term)) {
    return;
}

// Get audiobooks in this topic
$books = get_posts([
    'post_type'      => 'audiobook',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'tax_query'      => [
        [
            'taxonomy' => 'topics',
            'field'    => 'term_id',
            'terms'    => $term->term_id,
        ],
    ],
]);

if (!$books) {
    return;
}

// Count authors
$author_counts = [];

foreach ($books as $book_id) {
    $author = get_field('book_author', $book_id);
    if (!$author) continue;

    $author_counts[$author] = ($author_counts[$author] ?? 0) + 1;
}

arsort($author_counts);
$author_counts = array_slice($author_counts, 0, 6, true);
?>

<aside class="hidden lg:block">

    <div class="bg-ui-surface border border-ui-border rounded-xl p-6 shadow-sm">

        <h3 class="text-xs font-bold text-ui-subtext uppercase tracking-wider mb-4">
            Top Authors
        </h3>

        <ul class="space-y-3">
            <?php foreach ($author_counts as $author => $count): ?>
                <li>
                    <a href="<?= esc_url(home_url('/authors/' . sanitize_title($author))); ?>"
                        class="flex items-center justify-between text-sm text-ui-textSoft
                               hover:text-ui-text font-medium transition">

                        <span><?= esc_html($author); ?></span>

                        <span class="text-xs px-2 py-0.5 rounded bg-ui-bg border border-ui-border text-ui-subtext">
                            <?= intval($count); ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>

</aside>