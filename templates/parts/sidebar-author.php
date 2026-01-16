<?php

/**
 * Author Sidebar – Related Topics
 */

$author_slug = get_query_var('author_name');
$author_name = ucwords(str_replace('-', ' ', $author_slug));

// Fetch books by author
$books = get_posts([
    'post_type'      => 'audiobook',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
        [
            'key'     => 'book_author',
            'value'   => $author_name,
            'compare' => 'LIKE',
        ],
    ],
]);

if (!$books) return;

// Collect topics
$topic_counts = [];

foreach ($books as $book_id) {
    $topics = wp_get_post_terms($book_id, 'topics');
    foreach ($topics as $topic) {
        $topic_counts[$topic->term_id]['term']  = $topic;
        $topic_counts[$topic->term_id]['count'] =
            ($topic_counts[$topic->term_id]['count'] ?? 0) + 1;
    }
}

arsort($topic_counts);
$topic_counts = array_slice($topic_counts, 0, 6);
?>

<aside class="hidden lg:block">
    <div class="bg-ui-surface/40 backdrop-blur border border-ui-border rounded-xl p-6">

        <h3 class="text-xs font-bold text-ui-subtext uppercase tracking-wider mb-4">
            Related Topics
        </h3>

        <ul class="space-y-3">
            <?php foreach ($topic_counts as $item): ?>
                <li>
                    <a href="<?= esc_url(get_term_link($item['term'])); ?>"
                        class="flex items-center justify-between text-sm text-ui-subtext
                              hover:text-ui-text font-medium transition no-underline hover:no-underline">

                        <span><?= esc_html($item['term']->name); ?></span>

                        <span class="text-xs px-2 py-0.5 rounded bg-ui-bg border border-ui-border">
                            <?= intval($item['count']); ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</aside>