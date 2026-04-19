let lastScrollTop = 0;

window.addEventListener("scroll", () => {
    const nav = document.querySelector(".navbar");
    let st = window.pageYOffset || document.documentElement.scrollTop;

    if (st > 50) {
        nav.classList.add("shrink");
    } else {
        nav.classList.remove("shrink");
    }

    lastScrollTop = st <= 0 ? 0 : st;
});
