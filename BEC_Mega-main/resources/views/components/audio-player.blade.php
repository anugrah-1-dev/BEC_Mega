<style>
    #global-mute-toggle {
        position: fixed;
        bottom: 25px;
        left: 25px;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.2);
        width: 52px;
        height: 52px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    #global-mute-toggle:hover {
        transform: scale(1.1) translateY(-2px);
        background: rgba(79, 70, 229, 0.8);
        border-color: rgba(255, 255, 255, 0.6);
    }
    #global-mute-toggle.playing {
        animation: globalAudioPulse 2s infinite;
        background: rgba(79, 70, 229, 0.6);
    }
    @keyframes globalAudioPulse {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
    #global-mute-toggle svg {
        width: 26px;
        height: 26px;
        fill: currentColor;
    }
    .audio-hint {
        position: absolute;
        left: 65px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }
    #global-mute-toggle:hover .audio-hint {
        opacity: 1;
        transform: translateX(0);
    }
</style>

<audio id="global-bg-audio" loop>
    <source src="{{ asset('assets/audio/audiobec.mp3') }}" type="audio/mpeg">
</audio>

<button id="global-mute-toggle" title="Putar/Matikan Musik">
    <svg id="global-icon-unmuted" viewBox="0 0 24 24" style="display: none;"><path d="M14,3.23V5.29C16.89,6.15 19,8.83 19,12C19,15.17 16.89,17.85 14,18.71V20.77C18.03,19.86 21,16.28 21,12C21,7.72 18.03,4.14 14,3.23M16.5,12C16.5,10.23 15.5,8.71 14,7.97V16.02C15.5,15.29 16.5,13.77 16.5,12M3,9V15H7L12,20V4L7,9H3Z" /></svg>
    <svg id="global-icon-muted" viewBox="0 0 24 24"><path d="M12,4L9.91,6.09L12,8.18M4.27,3L3,4.27L7.73,9H3V15H7L12,20V13.27L16.25,17.53C15.58,18.04 14.83,18.46 14,18.7V20.77C15.38,20.45 16.63,19.82 17.68,18.96L19.73,21L21,19.73L12,10.73M19,12C19,12.94 18.8,13.82 18.46,14.63L19.97,16.14C20.63,14.91 21,13.5 21,12C21,7.72 18.03,4.14 14,3.23V5.29C16.89,6.15 19,8.83 19,12M16.5,12C16.5,10.23 15.5,8.71 14,7.97V10.18L16.45,12.63C16.48,12.43 16.5,12.22 16.5,12Z" /></svg>
    <span class="audio-hint">Musik Latar</span>
</button>

<script>
    (function() {
        const audio = document.getElementById('global-bg-audio');
        const toggle = document.getElementById('global-mute-toggle');
        const iconMuted = document.getElementById('global-icon-muted');
        const iconUnmuted = document.getElementById('global-icon-unmuted');

        function updateUI() {
            if (!audio || !toggle) return;
            if (audio.paused) {
                iconMuted.style.display = 'block';
                iconUnmuted.style.display = 'none';
                toggle.classList.remove('playing');
            } else {
                iconMuted.style.display = 'none';
                iconUnmuted.style.display = 'block';
                toggle.classList.add('playing');
            }
        }

        function toggleAudio() {
            if (audio.paused) {
                audio.play().then(() => {
                    localStorage.setItem('audio-enabled', 'true');
                    updateUI();
                }).catch(err => {
                    console.log("Autoplay blocked or failed:", err);
                });
            } else {
                audio.pause();
                localStorage.setItem('audio-enabled', 'false');
                updateUI();
            }
        }

        if (toggle) toggle.addEventListener('click', toggleAudio);

        // Auto-play attempt on first interaction or if enabled
        window.addEventListener('load', () => {
            const wasEnabled = localStorage.getItem('audio-enabled');
            if (toggle) toggle.style.display = 'flex';

            if (wasEnabled !== 'false') {
                const startOnInteraction = () => {
                    audio.play().then(() => {
                        updateUI();
                        document.removeEventListener('click', startOnInteraction);
                    }).catch(() => {});
                };
                document.addEventListener('click', startOnInteraction);
            }
            updateUI();
        });

        // Listen for internal play/pause events
        if (audio) {
            audio.onplay = () => {
                localStorage.setItem('audio-enabled', 'true');
                updateUI();
            };
            audio.onpause = () => {
                if (audio.paused) localStorage.setItem('audio-enabled', 'false');
                updateUI();
            };
        }
    })();
</script>
