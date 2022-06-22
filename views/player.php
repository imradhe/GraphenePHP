<?php include("partials/head.php");?>
<style>
    .lyrics{
        transition: ease-in .6s;
    }
</style>
<center class="container mt-5">
    <div class="lyrics">
        Hello there! 😉<br>Press play to know more about me
    </div>
    
    <input type="range" name="player" id="player" value="100" min="100" max="1100" readonly style="pointer-events: none;" class="form-range" >
    
    <button id="playBtn" class="btn btn-success"> <i class="bi bi-play-fill"></i> </button>
    <button id="pauseBtn" class="btn btn-secondary"> <i class="bi bi-pause-fill"></i> </button>
    <button id="stopBtn" class="btn btn-danger"> <i class="bi bi-octagon-fill"></i> </button>
</center>


<script>
    let player = document.querySelector("#player")
    let playBtn = document.querySelector("#playBtn")
    let pauseBtn = document.querySelector("#pauseBtn")
    let stopBtn = document.querySelector("#stopBtn")

    const lyrics = document.querySelector('.lyrics')    

    let syncData = [
        {"start":"100", "heading" : "I am", "text": "Radhe Shyam Salopanthula"} ,
        {"start":"200", "heading" : "I am", "text": "Full Stack Developer"} ,
        {"start":"300", "heading" : "I am", "text": "Graphic Designer"} ,
        {"start":"400", "heading" : "I am", "text": "Music Composer"},
        {"start":"500", "heading" : "I am", "text": "Singer/Songwriter"},
        {"start":"600", "heading" : "I am", "text": "Being Human"},
        
        {"start":"700", "heading" : "I am", "text": "Radhe Shyam Salopanthula"}
    ]

function sync(index){
    syncData.forEach((item) => {
            if (player.value >= item.start) lyrics.innerHTML = item.heading +"<br>"+ item.text + "<br>" + index
        })
}

player.addEventListener("change", (index) =>{
    sync(index)
})

        

    pauseBtn.disabled = true
    pauseBtn.style.display = "none"
    stopBtn.disabled = true

    playBtn.addEventListener("click", ()=>{
        play()
        playBtn.disabled = true
        pauseBtn.disabled = false
        stopBtn.disabled = false
    })

    let start = 100, end = 1100
    let duration = 20000
    let range = end - start
    let increment = end > start ? 1 : -1
    let step = Math.abs(Math.floor(duration / range))
    let slider = document.querySelector("#player")

function play() {
    playBtn.style.display = "none"
    pauseBtn.style.display = "inline"
    current = start
    timer = setInterval(() => {
    current += increment
    slider.value = current
    sync(current)
    if (current == end) {
     playBtn.style.display = "inline"
     pauseBtn.style.display = "none"
     clearInterval(timer)
     start = 100
     player.value = start
     pauseBtn.disabled = true
     stopBtn.disabled = true
     playBtn.disabled = false
    }
   }, step)
   

   pauseBtn.addEventListener("click", ()=>{
    playBtn.style.display = "inline"
    pauseBtn.style.display = "none"
    pauseBtn.disabled = true
    stopBtn.disabled = false
    playBtn.disabled = false
    let last = current
    clearInterval(timer)
    slider.value = last
    start = last
    pausePosition.innerHTML = last
    sync(current)
   })

   stopBtn.addEventListener("click", ()=>{
    playBtn.style.display = "inline"
    pauseBtn.style.display = "none"
    pauseBtn.disabled = true
    stopBtn.disabled = true
    playBtn.disabled = false
    let last = 100
    current = player.getAttribute("min")
    clearInterval(timer)
    slider.value = last
    start = last
   })
 }        
</script>