document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("stars-background");

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

    // --- PARTICULE ---
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
            this.speedX = random(-0.1, 0.1);
            this.speedY = random(-0.1, 0.1);
            this.changeTimer = random(50, 200);
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            // Smooth change of direction
            this.changeTimer--;
            if (this.changeTimer <= 0) {
                this.speedX += random(-0.1, 0.1);
                this.speedY += random(-0.1, 0.1);
                this.changeTimer = random(50, 200);

                // Limit max speed
                this.speedX = Math.min(Math.max(this.speedX, -0.1), 0.1);
                this.speedY = Math.min(Math.max(this.speedY, -0.1), 0.1);
            }

            // Loop on edges
            if (this.x < 0) this.x = canvas.width;
            if (this.x > canvas.width) this.x = 0;
            if (this.y < 0) this.y = canvas.height;
            if (this.y > canvas.height) this.y = 0;
        }
        draw() {
			ctx.save(); // protejează setările canvas-ului

			ctx.shadowBlur = 15;                 // cât de puternic e glow-ul
			ctx.shadowColor = "rgba(255,215,100,0.8)"; // culoarea glow-ului

			ctx.fillStyle = "rgba(255,215,100,0.7)";   // culoarea particulei
			ctx.beginPath();
			ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
			ctx.fill();

			ctx.restore(); // revine la setările normale
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

