document.addEventListener("mousemove", (e) => {
    let x = (e.clientX / window.innerWidth - 0.5) * 2;
    let y = (e.clientY / window.innerHeight - 0.5) * 2;

    // Background parallax
    const bg = document.querySelector(".bg-overlay");
    if (bg) {
        bg.style.transform = `translate(${x * 10}px, ${y * 10}px) scale(1.05)`;
    }

    // Stars parallax
    const stars = document.getElementById("stars");
    if (stars) {
        stars.style.transform = `translate(${x * 25}px, ${y * 25}px)`;
    }

    // Logo parallax
    const logo = document.querySelector(".hero-logo");
    if (logo) {
        logo.style.transform = `translate(${x * 6}px, ${y * 6}px)`;
    }
});
