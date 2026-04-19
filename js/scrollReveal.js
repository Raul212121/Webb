document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".reveal-up");

    if (!("IntersectionObserver" in window) || elements.length === 0) {
        elements.forEach(el => el.classList.add("visible"));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    obs.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.18
        }
    );

    elements.forEach(el => observer.observe(el));
});
