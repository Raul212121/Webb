document.addEventListener("mousemove", (e) => {
    const lights = document.querySelectorAll(".section-box .light");

    lights.forEach((light) => {
        light.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
    });
});
