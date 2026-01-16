<?php

/**
 * Library Hero Section
 */
?>

<section class="bg-gradient-to-b from-brand-darker to-ui-bg border-b border-ui-border">
    <div class="max-w-7xl mx-auto px-4 py-16">

        <!-- Eyebrow -->
        <p class="text-xs font-medium tracking-widest uppercase text-ui-subtext mb-3">
            Library
        </p>

        <!-- Title -->
        <h1 class="text-4xl lg:text-5xl font-bold text-ui-text mb-4">
            Audiobook Library
        </h1>

        <!-- Description -->
        <p class="max-w-2xl text-ui-subtext text-base leading-relaxed mb-8">
            Browse concise audio summaries across topics that matter.
            Learn faster, retain more, and apply ideas immediately.
        </p>

        <!-- CTA -->
        <a href="<?php echo esc_url(home_url('/audiobooks')); ?>"
            class="inline-flex items-center gap-2 rounded-full bg-brand-primary px-6 py-3 
                  text-sm font-semibold text-white shadow-lg shadow-brand-primary/20
                  hover:shadow-brand-primary/40 transition">
            View all audiobooks
            <i class="fa-solid fa-arrow-right text-xs opacity-80"></i>
        </a>

    </div>
</section>