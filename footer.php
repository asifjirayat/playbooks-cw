<?php

/**
 * Theme Footer
 */
?>

<footer class="border-t border-slate-800 bg-slate-950 mt-16">
    <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">

        <!-- Left Branding -->
        <div class="flex items-center gap-2">
            <span class="font-semibold tracking-[0.18em] uppercase text-slate-400">
                Concentrated Wisdom
            </span>
            <span class="script-logo text-sm text-brand-primary font-bold">
                Playbooks
            </span>
        </div>

        <!-- Copyright -->
        <p class="text-center sm:text-left text-slate-500">
            © <?php echo date('Y'); ?> Concentrated Wisdom. All rights reserved.
        </p>

        <!-- Footer Menu -->
        <div class="flex gap-4">
            <a href="#" class="hover:text-slate-300 transition-colors">Privacy</a>
            <a href="#" class="hover:text-slate-300 transition-colors">Terms</a>
            <a href="#" class="hover:text-slate-300 transition-colors">Contact</a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>