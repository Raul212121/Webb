<?php
// === CONFIG ===
$redirectUrl = "https://luramt2.ro/shop"; // unde vrei să ajungă după 5 secunde
$seconds = 5;
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<title>Plată reușită</title>

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        font-family: "Segoe UI", Arial, sans-serif;
        background: radial-gradient(circle at 20% 0%, #2d1f0e, #0c0d14 55%, #000 100%);
        color: #f5e8c9;
        overflow: hidden;
    }

    /* Glow global */
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background:
            radial-gradient(circle at 10% -10%, rgba(255, 210, 120, 0.18), transparent 55%),
            radial-gradient(circle at 90% 110%, rgba(255, 120, 80, 0.32), transparent 55%);
        mix-blend-mode: screen;
        opacity: 0.7;
        pointer-events: none;
        z-index: -1;
    }

    .center-box {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 520px;
        padding: 40px;
        text-align: center;
        border-radius: 12px;

        background: linear-gradient(145deg, rgba(30, 22, 18, 0.95), rgba(10, 10, 14, 0.97));
        box-shadow:
            0 0 18px rgba(255, 190, 120, 0.28),
            0 0 40px rgba(0, 0, 0, 0.9),
            inset 0 0 18px rgba(255, 185, 90, 0.15);
    }

    .title {
        font-size: 32px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
        color: #ffe7b3;
        text-shadow: 0 0 12px rgba(255, 180, 90, 0.6);
    }

    .message {
        font-size: 15px;
        color: #dbcbb0;
        margin-bottom: 25px;
    }

    .timer {
        margin-top: 20px;
        font-size: 48px;
        font-weight: bold;
        color: #ffd77b;
        text-shadow: 0 0 12px rgba(255, 200, 100, 0.65);
    }

    .button {
        display: inline-block;
        margin-top: 25px;
        padding: 12px 35px;
        font-size: 15px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: bold;
        color: #3a1a04;

        background: radial-gradient(circle at top, #ffec9a, #e2972f);
        border: 1px solid #f2c067;
        border-radius: 6px;

        box-shadow:
            0 0 10px rgba(255, 210, 120, 0.7),
            0 0 20px rgba(180, 90, 20, 0.6);
        transition: 0.2s ease-in-out;
    }

    .button:hover {
        transform: translateY(-2px);
        box-shadow:
            0 0 12px rgba(255, 220, 150, 0.9),
            0 0 26px rgba(200, 110, 30, 0.9);
    }
	
	#stars-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    pointer-events: none;
    overflow: hidden;
	}
	
	#stars-canvas {
		position: absolute;
		top: 0;
		left: 0;
	}
</style>

<script>
    let seconds = <?php echo $seconds; ?>;
    function countdown() {
        document.getElementById("timer").innerText = seconds;
        seconds--;

        if (seconds < 0) {
            window.location.href = "<?php echo $redirectUrl; ?>";
        } else {
            setTimeout(countdown, 1000);
        }
    }
    window.onload = countdown;
</script>


</head>
<body>

<div class="center-box">
	<div id="particles-bg"></div>
	<div id="stars-background"></div>
    <div class="title">Eroare</div>
    <div class="line"></div>

    <div class="message">
        A intervenit o problemă!!!<br>
        Vei fi redirecționat automat în <b id="timer"><?php echo $seconds; ?></b> secunde.
    </div>

    <a class="button" href="<?php echo $redirectUrl; ?>">Mergi acum</a>

    <div class="timer" id="timerDisplay"></div>
</div>
<script src="<?php print $shop_url; ?>js/stars.js"></script>
</body>
</html>
