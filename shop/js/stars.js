window.addEventListener("load", () => {
    const container = document.getElementById("stars-background");
    if (!container) {
        console.error("⚠️ stars-background nu există în DOM.");
        return;
    }

    // Creez canvasul pentru stele
    const canvas = document.createElement("canvas");
    canvas.id = "stars-canvas";
    container.appendChild(canvas);

    const ctx = canvas.getContext("2d");

    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener("resize", resize);

    // Configurare particule
    const starCount = 70;
    const stars = [];

    function random(min, max) {
        return Math.random() * (max - min) + min;
    }

    class Star {
        constructor() {
            this.reset();
        }
        reset() {
            this.x = random(0, canvas.width);
            this.y = random(0, canvas.height);
            this.size = random(1, 3);
            this.speedX = random(-0.15, 0.15);
            this.speedY = random(-0.15, 0.15);
            this.changeTimer = random(40, 200);
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            this.changeTimer--;
            if (this.changeTimer <= 0) {
                this.speedX += random(-0.1, 0.1);
                this.speedY += random(-0.1, 0.1);
                this.changeTimer = random(40, 200);

                this.speedX = Math.min(Math.max(this.speedX, -0.2), 0.2);
                this.speedY = Math.min(Math.max(this.speedY, -0.2), 0.2);
            }

            if (this.x < 0) this.x = canvas.width;
            if (this.x > canvas.width) this.x = 0;
            if (this.y < 0) this.y = canvas.height;
            if (this.y > canvas.height) this.y = 0;
        }
        draw() {
            ctx.save();
            ctx.shadowBlur = 15;
            ctx.shadowColor = "rgba(255,215,100,0.9)";
            ctx.fillStyle = "rgba(255,215,100,0.8)";
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        }
    }

    for (let i = 0; i < starCount; i++) stars.push(new Star());

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        stars.forEach(star => {
            star.update();
            star.draw();
        });
        requestAnimationFrame(animate);
    }

    animate();
});
