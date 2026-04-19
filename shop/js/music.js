window.addEventListener("load", () => {

    const audio = document.getElementById("bgm");
    if (!audio) return;

    audio.volume = 0.15;

    // restore previous time
    const savedPos = localStorage.getItem("bgm_pos");
    if (savedPos) {
        audio.currentTime = parseFloat(savedPos);
    }

    // update every 400ms
    setInterval(() => {
        localStorage.setItem("bgm_pos", audio.currentTime);
    }, 400);

    // force play on first click ONLY ONCE
    const playMusic = () => {
        audio.muted = false;
        audio.play().catch(() => {});
        document.removeEventListener("click", playMusic);
    };

    document.addEventListener("click", playMusic);
});
