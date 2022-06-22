<?php
locked();
?>



<center>
    <video class="player" style="max-height:80vh;max-width:80vw;">
    <source src="<?php assets("video/sample.mp4");?>">
    </video> 
<div class="subtitles" style="margin: 10px 20px; margin-top:-80px; color:white;">
    <h3>
        0 | Stephen Strange
        0.18 | You were now called before the Illuminati
        0.44 | Meet the smartest man Alive
        0.60 | Dr. Sheldon Cooper
        0.78 | Bazinga!
    </h3>
</div>
<br><br><br>
<h1>Press Spacebar to play</h1>
</center>


<script>
    const player = document.querySelector('.player')
const subtitles = document.querySelector('.subtitles h3')
const lines = subtitles.textContent.trim().split('\n')

subtitles.innerText = ''

let syncData = []

lines.map((line, index) => {
    const [time, text] = line.trim().split('|')
    syncData.push({'start': time.trim(), 'text': text.trim()})
})

player.addEventListener('timeupdate', () => {
    syncData.forEach((item) => {
        if (player.currentTime >= (item.start)*10) subtitles.innerHTML = item.text
    })
})
document.addEventListener("load", function(){
    player.play()
})

let play = false
let pause = true

player.addEventListener("click", function(){ 
    if(play){
        player.pause()
        play = false
        pause = true
    }else{
        player.play()
        play = true
        pause = false
    }
})

player.addEventListener('contextmenu', event => event.preventDefault());

addEventListener("keydown",function(e){
                 if(e.keyCode == "32"){
                      e.preventDefault()
                      player.click()
                    }
             })

</script>