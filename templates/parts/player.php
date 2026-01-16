<?php

/**
 * Player Partial - WaveSurfer continuous waveform
 * Expects:
 *  - cw_audio_url (string)
 *  - cw_locked (bool)
 *
 * Notes:
 *  - Uses WaveSurfer.js via CDN (unpkg). If you prefer to self-host, swap the script tag.
 *  - If WaveSurfer fails to load or audio is missing, the player falls back to disabled controls.
 */

$audio_url = get_query_var('cw_audio_url');
$is_locked = get_query_var('cw_locked');
?>

<!-- include WaveSurfer (UMD build) -->
<script src="https://unpkg.com/wavesurfer.js@7"></script>

<div id="cw-inline-player" class="bg-ui-surface/40 border border-ui-border rounded-xl p-5 mt-6">
    <div class="flex items-center gap-4">

        <!-- Play/Pause Button -->
        <button id="cw-inline-play"
            class="w-12 h-12 rounded-full flex items-center justify-center text-white
            <?php if ($is_locked): ?>
              bg-slate-800 text-slate-500 cursor-not-allowed
            <?php else: ?>
              bg-brand-primary hover:brightness-110
            <?php endif; ?>
            transition"
            aria-label="Play/Pause">
            <i class="fa-solid fa-play" id="cw-inline-icon" aria-hidden="true"></i>
        </button>

        <!-- Waveform container (WaveSurfer will draw inside) -->
        <div class="flex-1">
            <div class="flex items-center justify-between text-xs text-ui-subtext font-medium mb-2 w-full">
                <span>Audio Summary</span>
                <span id="cw-inline-duration" class="text-ui-subtext text-xs"><?php echo esc_html($duration ?? ''); ?></span>
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
            <button id="cw-sticky-toggle" class="text-ui-text text-2xl" aria-label="Toggle player">
                <i class="fa-solid fa-chevron-up"></i>
            </button>

            <div>
                <h4 class="text-sm font-bold text-ui-text"><?php the_title(); ?></h4>
                <p class="text-xs text-ui-subtext">Playing…</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button id="cw-sticky-back" class="text-ui-subtext hover:text-ui-text" aria-label="Rewind 10s">
                <i class="fa-solid fa-backward-15"></i>
            </button>

            <button id="cw-sticky-play"
                class="w-10 h-10 bg-brand-primary text-white rounded-full flex items-center justify-center hover:brightness-110 transition"
                aria-label="Play/Pause (sticky)">
                <i class="fa-solid fa-play" id="cw-sticky-icon" aria-hidden="true"></i>
            </button>

            <button id="cw-sticky-forward" class="text-ui-subtext hover:text-ui-text" aria-label="Forward 10s">
                <i class="fa-solid fa-forward-15"></i>
            </button>
        </div>
    </div>

    <div class="h-1 bg-ui-bg w-full cursor-pointer group">
        <div id="cw-sticky-progress" class="h-full bg-brand-primary w-0 relative group-hover:h-2 transition-all"></div>
    </div>
</div>

<!-- Fallback audio element (hidden) - only used if WaveSurfer is not available -->
<?php if (!$is_locked && $audio_url): ?>
    <audio id="cw-audio-fallback" src="<?= esc_url($audio_url); ?>" preload="metadata" style="display:none"></audio>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const locked = <?= $is_locked ? 'true' : 'false'; ?>;
        const audioUrl = <?= $audio_url ? "'" . esc_js($audio_url) . "'" : 'null'; ?>;

        // Elements
        const heroPlayBtn = document.getElementById('playInlineTrigger'); // hero button
        const inlineBtn = document.getElementById('cw-inline-play');
        const inlineIcon = document.getElementById('cw-inline-icon');
        const stickyBtn = document.getElementById('cw-sticky-play');
        const stickyIcon = document.getElementById('cw-sticky-icon');
        const stickyPanel = document.getElementById('cw-sticky-player');
        const toggleSticky = document.getElementById('cw-sticky-toggle');
        const progressBar = document.getElementById('cw-sticky-progress');
        const backBtn = document.getElementById('cw-sticky-back');
        const forwardBtn = document.getElementById('cw-sticky-forward');
        const durationEl = document.getElementById('cw-inline-duration');

        // Expose global toggle for external controls
        window.cwTogglePlay = () => {
            if (waveSurferInstance) waveSurferInstance.playPause();
        };

        // Prepare a fallback in case WaveSurfer fails to load
        const fallbackAudio = document.getElementById('cw-audio-fallback');

        // waveSurferInstance will be the WaveSurfer object when ready
        let waveSurferInstance = null;
        let wsReady = false;

        // Helper to set icons
        function setIconsPlayingState(isPlaying) {
            if (inlineIcon) {
                if (isPlaying) inlineIcon.classList.replace('fa-play', 'fa-pause');
                else inlineIcon.classList.replace('fa-pause', 'fa-play');
            }
            if (stickyIcon) {
                if (isPlaying) stickyIcon.classList.replace('fa-play', 'fa-pause');
                else stickyIcon.classList.replace('fa-pause', 'fa-play');
            }
            if (heroPlayBtn) {
                const heroIcon = heroPlayBtn.querySelector('i');
                if (heroIcon) {
                    if (isPlaying) heroIcon.classList.replace('fa-play', 'fa-pause');
                    else heroIcon.classList.replace('fa-pause', 'fa-play');
                }
            }
        }

        // If locked or no audio URL, disable play handlers
        if (locked || !audioUrl) {
            if (heroPlayBtn) {
                heroPlayBtn.addEventListener('click', () => {
                    // redirect to membership if locked, or do nothing if no audio
                    if (locked) window.location.href = '/membership';
                });
            }
            // Make inline & sticky buttons no-op (or show membership page)
            if (inlineBtn) inlineBtn.addEventListener('click', () => {
                if (locked) window.location.href = '/membership';
            });
            if (stickyBtn) stickyBtn.addEventListener('click', () => {
                if (locked) window.location.href = '/membership';
            });
            // disable back/forward
            if (backBtn) backBtn.addEventListener('click', (e) => e.preventDefault());
            if (forwardBtn) forwardBtn.addEventListener('click', (e) => e.preventDefault());
            return;
        }

        // Try to initialize WaveSurfer. If not available, fallback to native <audio>
        function initWaveSurfer() {
            try {
                if (!window.WaveSurfer) throw new Error('WaveSurfer not found');

                // create instance
                waveSurferInstance = WaveSurfer.create({
                    container: '#cw-waveform',
                    waveColor: 'rgba(255,255,255,0.12)', // light/low contrast
                    progressColor: 'rgba(255,188,0,0.92)', // brand yellow
                    backend: 'WebAudio',
                    cursorWidth: 0,
                    normalize: true,
                    responsive: true,
                    height: 48, // px, will be clipped by container h-8 if smaller; adjust if you want taller waveform
                    partialRender: true,
                    scrollParent: false
                });

                // load audio (can be a Bunny signed URL later)
                waveSurferInstance.load(audioUrl);

                waveSurferInstance.on('ready', () => {
                    wsReady = true;

                    // show duration (mm:ss)
                    const dur = waveSurferInstance.getDuration();
                    if (durationEl && dur && !isNaN(dur)) {
                        const min = Math.floor(dur / 60);
                        const sec = Math.floor(dur % 60).toString().padStart(2, '0');
                        durationEl.textContent = `${min}:${sec}`;
                    }

                    // bind play/pause icon sync
                    waveSurferInstance.on('play', () => {
                        setIconsPlayingState(true);
                        stickyPanel.classList.remove('translate-y-full');
                    });
                    waveSurferInstance.on('pause', () => {
                        setIconsPlayingState(false);
                    });
                    waveSurferInstance.on('finish', () => {
                        setIconsPlayingState(false);
                        if (progressBar) progressBar.style.width = '100%'; // ended
                    });

                    // update progress bar on audioprocess/timeupdate
                    waveSurferInstance.on('audioprocess', () => {
                        const cur = waveSurferInstance.getCurrentTime();
                        const dur = waveSurferInstance.getDuration() || 1;
                        if (progressBar) progressBar.style.width = ((cur / dur) * 100) + '%';
                    });
                });

                // Attach UI controls
                inlineBtn.addEventListener('click', (e) => {
                    waveSurferInstance.playPause();
                });

                stickyBtn.addEventListener('click', (e) => {
                    waveSurferInstance.playPause();
                });

                heroPlayBtn && heroPlayBtn.addEventListener('click', (e) => {
                    waveSurferInstance.playPause();
                });

                // back/forward seeking (10s)
                backBtn && backBtn.addEventListener('click', () => {
                    const t = Math.max(0, waveSurferInstance.getCurrentTime() - 10);
                    waveSurferInstance.seekTo(t / waveSurferInstance.getDuration());
                });
                forwardBtn && forwardBtn.addEventListener('click', () => {
                    const t = Math.min(waveSurferInstance.getDuration(), waveSurferInstance.getCurrentTime() + 10);
                    waveSurferInstance.seekTo(t / waveSurferInstance.getDuration());
                });

            } catch (err) {
                // WaveSurfer initialization failed — fallback
                console.warn('WaveSurfer init failed:', err);
                initFallbackAudio();
            }
        } // initWaveSurfer

        // Fallback: use native audio element for playback & simple progress update
        function initFallbackAudio() {
            if (!fallbackAudio) return;
            // hero control
            heroPlayBtn && heroPlayBtn.addEventListener('click', () => {
                if (fallbackAudio.paused) fallbackAudio.play();
                else fallbackAudio.pause();
            });
            // inline & sticky
            inlineBtn && inlineBtn.addEventListener('click', () => {
                if (fallbackAudio.paused) fallbackAudio.play();
                else fallbackAudio.pause();
            });
            stickyBtn && stickyBtn.addEventListener('click', () => {
                if (fallbackAudio.paused) fallbackAudio.play();
                else fallbackAudio.pause();
            });

            // sync icons
            fallbackAudio.addEventListener('play', () => setIconsPlayingState(true));
            fallbackAudio.addEventListener('pause', () => setIconsPlayingState(false));
            fallbackAudio.addEventListener('timeupdate', () => {
                if (!fallbackAudio.duration || isNaN(fallbackAudio.duration)) return;
                const p = fallbackAudio.currentTime / fallbackAudio.duration;
                if (progressBar) progressBar.style.width = (p * 100) + '%';
            });
            fallbackAudio.addEventListener('loadedmetadata', () => {
                if (fallbackAudio.duration && durationEl) {
                    const min = Math.floor(fallbackAudio.duration / 60);
                    const sec = Math.floor(fallbackAudio.duration % 60).toString().padStart(2, '0');
                    durationEl.textContent = `${min}:${sec}`;
                }
            });

            // back/forward
            backBtn && backBtn.addEventListener('click', () => fallbackAudio.currentTime = Math.max(0, fallbackAudio.currentTime - 10));
            forwardBtn && forwardBtn.addEventListener('click', () => fallbackAudio.currentTime = Math.min(fallbackAudio.duration, fallbackAudio.currentTime + 10));
        }

        // Sticky panel toggle
        if (toggleSticky) toggleSticky.addEventListener('click', () => stickyPanel.classList.toggle('translate-y-full'));

        // Initialize WaveSurfer (preferred)
        initWaveSurfer();

    }); // DOMContentLoaded
</script>

<style>
    /* keep the waveform clipped to the inline player height and soften contrast */
    #cw-waveform {
        height: 32px;
    }

    /* ensure it aligns with h-8 (32px) */
    #cw-waveform .wavesurfer-canvas {
        height: 100% !important;
    }
</style>