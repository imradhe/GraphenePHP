<?php
locked();
?>


<a href="<?php assets("audio/10bears.mp3");?>" download="sample audio.mp3">Download</a>

<audio class="player" src="<?php assets("audio/10bears.mp3");?>" controls></audio> 

<div class="lyrics" style="display: none">
    0.125 | There were
    0.485 | 10 in his bed
    1.685 | and the little
    2.245 | one said
    2.985 | Roll over!
</div>

<script>
    const player = document.querySelector('.player')
const lyrics = document.querySelector('.lyrics')
const lines = lyrics.textContent.trim().split('\n')

lyrics.removeAttribute('style')
lyrics.innerText = ''

let syncData = []

lines.map((line, index) => {
    const [time, text] = line.trim().split('|')
    syncData.push({'start': time.trim(), 'text': text.trim()})
})

player.addEventListener('timeupdate', () => {
    syncData.forEach((item) => {
        if (player.currentTime >= item.start) lyrics.innerText = item.text
    })
})
</script>