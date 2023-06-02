<?php
require('views/aibro.php');

$userMessage = "Best colleges for engineering in chennai";



?>
<!DOCTYPE html>
<html>
<head>
  <title>ChatGPT Typing Effect</title>
  <style>
    .chat-container {
      border: 1px solid #ccc;
      padding: 10px;
      white-space: pre-wrap !important;
      max-width: 20vw;
    }
    .message {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="chat-container">
    <div class="message">
        <div class="user"></div>
        <div class="AIBro">AI Bro: <span id="typing"></span></div>
    </div>
  </div>

  <script>
    // Simulate typing effect for HTML content
    function typeHTML(html, speed) {
      userMessage = "<?php echo $userMessage; ?>"
      document.querySelector('.user').innerHTML = "User:" + userMessage 

      const typingElement = document.querySelector('#typing');
      typingElement.innerHTML = ''; // Clear previous content
      let index = 0;
      const typingInterval = setInterval(() => {
        typingElement.innerHTML += html[index];
        index++;
        if (index >= html.length) {
          clearInterval(typingInterval);
        }
      }, speed);
    }

    // Delayed response from AI Bro
    setTimeout(() => {
      aiResponse = "<?php echo AIBro::response($userMessage); ?>";
      aiResponse = aiResponse.replace(/<br\s*[\/]?>/gi, "\n");
      const typingSpeed = 10; // Adjust typing speed (milliseconds per character)
      typeHTML(aiResponse, typingSpeed);
    }, 1500); // Adjust delay time (milliseconds)
  </script>
</body>
</html>