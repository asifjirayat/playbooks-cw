<?php

/**
 * Player Partial – WaveSurfer (working baseline)
 * Expects:
 *  - cw_audio_url (string)
 *  - cw_locked (bool)
 */

$audio_url = get_query_var('cw_audio_url');
$is_locked = get_query_var('cw_locked');
?>

<!-- WaveSurfer UMD (GLOBAL BUILD – REQUIRED) -->
<script src="https://unpkg.com/wavesurfer.js@7.12.1/dist/wavesurfer.min.js"></script>

<div id="cw-inline-player" class="bg-ui-surface/40 border border-ui-border rounded-xl p-5 mt-6">
    <div class="flex items-center gap-4">

        <!-- Play/Pause Button -->
        <button id="cw-inline-play"
            class="w-12 h-12 rounded-full flex items-center justify-center text-white
            <?= $is_locked ? 'bg-slate-800 text-slate-500 cursor-not-allowed' : 'bg-brand-primary hover:brightness-110' ?>"
            aria-label="Play/Pause">
            <i class="fa-solid fa-play" id="cw-inline-icon"></i>
        </button>

        <!-- Waveform -->
        <div class="flex-1">
            <div class="flex items-center justify-between text-xs text-ui-subtext font-medium mb-2">
                <span>Audio Summary</span>
                <span id="cw-inline-duration" class="text-ui-subtext text-xs"></span>
            </div>
            <div id="cw-waveform" class="w-full h-8 rounded-md overflow-hidden"></div>
        </div>

    </div>
</div>

<!-- Sticky Player -->
<div id="cw-sticky-player"
    class="fixed bottom-0 left-0 right-0 bg-ui-surface border-t border-ui-border shadow-xl translate-y-full transition-transform duration-300 z-50">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

        <div class="flex items-center gap-3">
            <button id="cw-sticky-toggle" class="text-ui-text text-2xl">
                <i class="fa-solid fa-chevron-up"></i>
            </button>

            <div>
                <h4 class="text-sm font-bold text-ui-text"><?php the_title(); ?></h4>
                <p class="text-xs text-ui-subtext">Playing…</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button id="cw-sticky-back" class="text-ui-subtext hover:text-ui-text">
                <i class="fa-solid fa-backward-15"></i>
            </button>

            <button id="cw-sticky-play"
                class="w-10 h-10 bg-brand-primary text-white rounded-full flex items-center justify-center hover:brightness-110">
                <i class="fa-solid fa-play" id="cw-sticky-icon"></i>
            </button>

            <button id="cw-sticky-forward" class="text-ui-subtext hover:text-ui-text">
                <i class="fa-solid fa-forward-15"></i>
            </button>
        </div>
    </div>

    <div class="h-1 bg-ui-bg w-full">
        <div id="cw-sticky-progress" class="h-full bg-brand-primary w-0"></div>
    </div>
</div>

<?php if (!$is_locked && $audio_url): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if (typeof WaveSurfer === 'undefined') {
                console.error('WaveSurfer not loaded');
                return;
            }

            const audioUrl = '<?= esc_js($audio_url); ?>';

            const heroBtn = document.getElementById('playInlineTrigger');
            const inlineBtn = document.getElementById('cw-inline-play');
            const inlineIcon = document.getElementById('cw-inline-icon');
            const stickyBtn = document.getElementById('cw-sticky-play');
            const stickyIcon = document.getElementById('cw-sticky-icon');
            const stickyPanel = document.getElementById('cw-sticky-player');
            const toggleSticky = document.getElementById('cw-sticky-toggle');
            const progressBar = document.getElementById('cw-sticky-progress');
            const durationEl = document.getElementById('cw-inline-duration');

            const ws = WaveSurfer.create({
                container: '#cw-waveform',
                waveColor: 'rgba(255,255,255,0.15)',
                progressColor: '#FFBC00',
                cursorWidth: 0,
                height: 32,
                normalize: true,
                responsive: true
            });

            ws.load(audioUrl);

            function syncIcons(isPlaying) {
                [inlineIcon, stickyIcon, heroBtn?.querySelector('i')].forEach(icon => {
                    if (!icon) return;
                    icon.classList.toggle('fa-play', !isPlaying);
                    icon.classList.toggle('fa-pause', isPlaying);
                });
            }

            ws.on('ready', () => {
                const dur = ws.getDuration();
                if (durationEl && dur) {
                    const m = Math.floor(dur / 60);
                    const s = Math.floor(dur % 60).toString().padStart(2, '0');
                    durationEl.textContent = `${m}:${s}`;
                }
            });

            ws.on('play', () => {
                syncIcons(true);
                stickyPanel.classList.remove('translate-y-full');
            });

            ws.on('pause', () => syncIcons(false));

            ws.on('audioprocess', () => {
                const p = ws.getCurrentTime() / ws.getDuration();
                progressBar.style.width = `${p * 100}%`;
            });

            [inlineBtn, stickyBtn, heroBtn].forEach(btn => {
                btn && btn.addEventListener('click', () => ws.playPause());
            });

            toggleSticky.addEventListener('click', () => {
                stickyPanel.classList.toggle('translate-y-full');
            });

            document.getElementById('cw-sticky-back')
                ?.addEventListener('click', () => ws.seekTo(Math.max(0, ws.getCurrentTime() - 10) / ws.getDuration()));

            document.getElementById('cw-sticky-forward')
                ?.addEventListener('click', () => ws.seekTo(Math.min(ws.getDuration(), ws.getCurrentTime() + 10) / ws.getDuration()));

        });
    </script>
<?php endif; ?>

<?php if ($is_locked): ?>
    <script>
        document.getElementById('cw-inline-play')?.addEventListener('click', () => {
            window.location.href = '/membership';
        });
    </script>
<?php endif; ?>

<style>
    #cw-waveform {
        height: 32px;
    }

    #cw-waveform canvas {
        height: 100% !important;
    }
</style>