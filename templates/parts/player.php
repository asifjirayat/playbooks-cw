<?php

/**
 * Player Partial – WaveSurfer
 */

$audio_url = get_query_var('cw_audio_url');
$is_locked = get_query_var('cw_locked');
?>

<script src="https://unpkg.com/wavesurfer.js@7.12.1/dist/wavesurfer.min.js"></script>

<div id="cw-inline-player" class="bg-ui-surface border border-ui-border rounded-xl p-5 mt-6 shadow-sm">
    <div class="flex items-center gap-4">

        <!-- Play/Pause Button -->
        <button id="cw-inline-play"
            class="w-12 h-12 rounded-full flex items-center justify-center text-white
            <?= $is_locked
                ? 'bg-ui-border text-ui-muted cursor-not-allowed'
                : 'bg-brand-primary hover:bg-blue-600 transition' ?>">
            <i class="fa-solid fa-play" id="cw-inline-icon"></i>
        </button>

        <!-- Waveform -->
        <div class="flex-1">
            <div class="flex items-center justify-between text-xs text-ui-subtext font-medium mb-2">
                <span>Audio Summary</span>
                <span id="cw-inline-duration" class="text-ui-subtext"></span>
            </div>
            <div id="cw-waveform" class="w-full h-8 rounded-md overflow-hidden bg-ui-bg"></div>
        </div>

    </div>
</div>

<!-- Sticky Player -->
<div id="cw-sticky-player" class="fixed bottom-8 left-0 right-0 z-50 flex justify-center items-center px-4">

    <div class="w-full max-w-[720px] bg-white/70 backdrop-blur-xl rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] p-4 flex items-center justify-between gap-4">

        <!-- Book Info -->
        <div class="hidden sm:flex items-center gap-3 w-1/4">
            <?php if (get_field('book_cover_url')): ?>
                <img src="<?= esc_url(get_field('book_cover_url')); ?>"
                    class="w-10 h-10 rounded-lg object-cover">
            <?php endif; ?>

            <div class="truncate">
                <p class="text-xs font-bold truncate"><?php the_title(); ?></p>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Listen</p>
            </div>
        </div>

        <!-- Controls -->
        <?php $skip = 10; // keep in sync with JS 
        ?>

        <div class="flex items-center justify-center gap-6 flex-1">

            <!-- BACK -->
            <button id="cw-sticky-back"
                class="flex flex-col items-center text-slate-600 hover:text-brand-primary transition">
                <i class="fa-solid fa-rotate-left text-lg"></i>
                <span class="text-[10px] mt-1 uppercase tracking-tight">Back <?= $skip ?>s</span>
            </button>

            <!-- PLAY -->
            <button id="cw-sticky-play"
                class="bg-brand-primary text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition-transform">
                <i class="fa-solid fa-play text-xl" id="cw-sticky-icon"></i>
            </button>

            <!-- FORWARD -->
            <button id="cw-sticky-forward"
                class="flex flex-col items-center text-slate-600 hover:text-brand-primary transition">
                <i class="fa-solid fa-rotate-right text-lg"></i>
                <span class="text-[10px] mt-1 uppercase tracking-tight">Forward <?= $skip ?>s</span>
            </button>

        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 w-1/4">
            <button class="text-slate-400 hover:text-brand-primary">
                <i class="fa-regular fa-bookmark"></i>
            </button>

            <button class="text-slate-400 hover:text-brand-primary">
                <i class="fa-solid fa-share-nodes"></i>
            </button>

            <div class="h-8 w-[1px] bg-slate-200 mx-1"></div>

        </div>

    </div>

</div>

<?php if (!$is_locked && $audio_url): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if (typeof WaveSurfer === 'undefined') return;

            const audioUrl = '<?= esc_js($audio_url); ?>';

            const heroBtn = document.getElementById('playInlineTrigger');
            const inlineBtn = document.getElementById('cw-inline-play');
            const inlineIcon = document.getElementById('cw-inline-icon');
            const stickyBtn = document.getElementById('cw-sticky-play');
            const stickyIcon = document.getElementById('cw-sticky-icon');
            const stickyPanel = document.getElementById('cw-sticky-player');
            const progressBar = document.getElementById('cw-sticky-progress');
            const durationEl = document.getElementById('cw-inline-duration');
            const SKIP_SECONDS = 10; // change this to 5 / 15 anytime

            const ws = WaveSurfer.create({
                container: '#cw-waveform',
                waveColor: '#cbd5e1', // ui.borderStrong-ish
                progressColor: '#2563eb', // brand.primary
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
            });

            ws.on('pause', () => syncIcons(false));

            ws.on('audioprocess', () => {
                if (!progressBar || !ws.getDuration()) return;

                const p = ws.getCurrentTime() / ws.getDuration();
                progressBar.style.width = `${p * 100}%`;
            });

            [inlineBtn, stickyBtn, heroBtn].forEach(btn => {
                btn && btn.addEventListener('click', () => ws.playPause());
            });

            document.getElementById('cw-sticky-back')
                ?.addEventListener('click', () => {
                    ws.seekTo(Math.max(0, ws.getCurrentTime() - SKIP_SECONDS) / ws.getDuration());
                });

            document.getElementById('cw-sticky-forward')
                ?.addEventListener('click', () => {
                    ws.seekTo(Math.min(ws.getDuration(), ws.getCurrentTime() + SKIP_SECONDS) / ws.getDuration());
                });

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