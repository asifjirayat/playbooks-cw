<?php

/**
 * Player Partial
 * Variables expected (passed from single-audiobook.php):
 * - cw_audio_url : string
 * - cw_locked    : bool
 *
 * Updated:
 * - exposes window.cwTogglePlay()
 * - hero Listen button (id="playInlineTrigger") now controls playback
 * - hero button icon kept in sync with player icons
 * - locked behavior: hero button redirects to /membership
 */

$audio_url = get_query_var('cw_audio_url');
$is_locked = get_query_var('cw_locked');
?>

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

        <!-- Waveform -->
        <div class="flex-1 flex flex-col">

            <!-- Top Row: Label + Duration -->
            <div class="flex items-center justify-between text-xs text-ui-subtext font-medium mb-2 w-full">
                <span>Audio Summary</span>
                <span id="cw-inline-duration" class="text-ui-subtext text-xs">
                    <?php echo esc_html($GLOBALS['duration'] ?? ''); ?>
                </span>
            </div>

            <!-- Full-width Waveform -->
            <div id="cw-waveform" class="flex items-end gap-[2px] h-8 opacity-60 w-full">
                <!-- JS generates bars -->
            </div>

        </div>


    </div>
</div>


<!-- Sticky Player -->
<div id="cw-sticky-player"
    class="fixed bottom-0 left-0 right-0 bg-ui-surface border-t border-ui-border shadow-xl 
            translate-y-full transition-transform duration-300 z-50">

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
                class="w-10 h-10 bg-brand-primary text-white rounded-full flex items-center justify-center 
                       hover:brightness-110 transition"
                aria-label="Play/Pause (sticky)">
                <i class="fa-solid fa-play" id="cw-sticky-icon" aria-hidden="true"></i>
            </button>

            <button id="cw-sticky-forward" class="text-ui-subtext hover:text-ui-text" aria-label="Forward 10s">
                <i class="fa-solid fa-forward-15"></i>
            </button>
        </div>
    </div>

    <div class="h-1 bg-ui-bg w-full cursor-pointer group">
        <div id="cw-sticky-progress"
            class="h-full bg-brand-primary w-0 relative group-hover:h-2 transition-all"></div>
    </div>
</div>


<!-- Audio Element -->
<?php if (!$is_locked && $audio_url): ?>
    <audio id="cw-audio" src="<?= esc_url($audio_url); ?>" preload="metadata"></audio>
<?php endif; ?>


<script>
    document.addEventListener("DOMContentLoaded", () => {

        const locked = <?= $is_locked ? 'true' : 'false'; ?>;

        // Elements
        const inlineBtn = document.getElementById("cw-inline-play");
        const inlineIcon = document.getElementById("cw-inline-icon");
        const stickyBtn = document.getElementById("cw-sticky-play");
        const stickyIcon = document.getElementById("cw-sticky-icon");
        const stickyPanel = document.getElementById("cw-sticky-player");
        const toggleSticky = document.getElementById("cw-sticky-toggle");
        const progressBar = document.getElementById("cw-sticky-progress");
        const wf = document.getElementById("cw-waveform");

        const audio = locked ? null : document.getElementById("cw-audio");

        // Hero listen button (in the hero template)
        const heroPlayBtn = document.getElementById("playInlineTrigger");

        /* -------------------------
           WAVEFORM GENERATION (dynamic)
           ------------------------- */
        if (wf) {
            for (let i = 0; i < 120; i++) {
                const bar = document.createElement("div");
                bar.className = "w-[2px] bg-brand-primary/80 rounded-full";
                bar.style.height = (Math.random() * 60 + 20) + "%";
                bar.style.animation = "cw-wave 1.2s ease-in-out infinite";
                bar.style.animationDelay = (Math.random() * 0.6) + "s";
                bar.style.animationPlayState = "paused";
                wf.appendChild(bar);
            }

        }

        function updateWaveformAnimation(play) {
            if (!wf) return;
            const bars = Array.from(wf.children);
            bars.forEach(b => b.style.animationPlayState = play ? "running" : "paused");
        }

        updateWaveformAnimation(false);

        /* -------------------------
           PLAY / PAUSE HANDLER
           ------------------------- */
        function setIconsPlayingState(isPlaying) {
            if (inlineIcon) {
                if (isPlaying) inlineIcon.classList.replace("fa-play", "fa-pause");
                else inlineIcon.classList.replace("fa-pause", "fa-play");
            }
            if (stickyIcon) {
                if (isPlaying) stickyIcon.classList.replace("fa-play", "fa-pause");
                else stickyIcon.classList.replace("fa-pause", "fa-play");
            }
            // hero icon sync (if present)
            if (heroPlayBtn) {
                const heroIcon = heroPlayBtn.querySelector("i");
                if (heroIcon) {
                    if (isPlaying) heroIcon.classList.replace("fa-play", "fa-pause");
                    else heroIcon.classList.replace("fa-pause", "fa-play");
                }
            }
        }

        function togglePlay() {
            if (locked || !audio) return;
            if (audio.paused) {
                audio.play();
            } else {
                audio.pause();
            }
        }

        // Expose a global toggle so external controls (hero button) can invoke playback
        window.cwTogglePlay = function() {
            togglePlay();
        };

        // Attach handlers to inline and sticky buttons
        if (inlineBtn) inlineBtn.addEventListener("click", () => {
            togglePlay();
        });
        if (stickyBtn) stickyBtn.addEventListener("click", () => {
            togglePlay();
        });

        // Hero Listen button controls playback (and handles locked state)
        if (heroPlayBtn) {
            heroPlayBtn.addEventListener("click", (e) => {
                if (locked) {
                    // redirect to membership page (adjust if you use a different membership URL)
                    window.location.href = "/membership";
                    return;
                }
                // call the global toggle
                window.cwTogglePlay && window.cwTogglePlay();
            });
        }

        /* -------------------------
           Audio events: sync UI
           ------------------------- */
        if (audio) {
            audio.addEventListener("play", () => {
                setIconsPlayingState(true);
                updateWaveformAnimation(true);
                // ensure sticky panel visible
                stickyPanel.classList.remove("translate-y-full");
            });

            audio.addEventListener("pause", () => {
                setIconsPlayingState(false);
                updateWaveformAnimation(false);
            });

            audio.addEventListener("ended", () => {
                setIconsPlayingState(false);
                updateWaveformAnimation(false);
            });

            audio.addEventListener("timeupdate", () => {
                if (!audio.duration || isNaN(audio.duration)) return;
                const p = audio.currentTime / audio.duration;
                if (progressBar) progressBar.style.width = (p * 100) + "%";
            });

            // Optional: on metadata load, show duration (if needed)
            audio.addEventListener("loadedmetadata", () => {
                const durEl = document.getElementById("cw-inline-duration");
                if (durEl && audio.duration) {
                    // format mm:ss
                    const sec = Math.floor(audio.duration % 60);
                    const min = Math.floor(audio.duration / 60);
                    durEl.textContent = `${min}:${sec.toString().padStart(2, '0')}`;
                }
            });
        }

        /* -------------------------
           STICKY PLAYER TOGGLE
           ------------------------- */
        if (toggleSticky) {
            toggleSticky.addEventListener("click", () => {
                stickyPanel.classList.toggle("translate-y-full");
            });
        }

        /* -------------------------
           BACKWARD / FORWARD
           ------------------------- */
        const backBtn = document.getElementById("cw-sticky-back");
        const forwardBtn = document.getElementById("cw-sticky-forward");

        if (audio) {
            if (backBtn) backBtn.addEventListener("click", () => audio.currentTime = Math.max(0, audio.currentTime - 10));
            if (forwardBtn) forwardBtn.addEventListener("click", () => audio.currentTime = Math.min(audio.duration, audio.currentTime + 10));
        } else {
            // if audio is not available (locked), make back/forward no-ops
            if (backBtn) backBtn.addEventListener("click", () => {});
            if (forwardBtn) forwardBtn.addEventListener("click", () => {});
        }

    });
</script>


<style>
    @keyframes cw-wave {

        0%,
        100% {
            transform: scaleY(0.3);
        }

        50% {
            transform: scaleY(1);
        }
    }
</style>