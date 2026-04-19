<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; overflow:hidden;">
<audio id="bgm" autoplay loop>
    <source src="/shop/audio/radio.mp3" type="audio/mpeg">
</audio>

<script>
// restore time
var audio = document.getElementById("bgm");

audio.volume = 0.1;

// try autoplay
audio.muted = false;
audio.play().catch(()=>{});

// restore last time
if (localStorage.getItem("bgm_pos")) {
    audio.currentTime = localStorage.getItem("bgm_pos");
}

// save time every 500ms
setInterval(() => {
    localStorage.setItem("bgm_pos", audio.currentTime);
}, 0);
</script>

</body>
</html>
