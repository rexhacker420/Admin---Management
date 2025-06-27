<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Neon Toast</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: "Rubik", sans-serif;
      background: #111;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .launch-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      padding: 1rem 2rem;
      font-size: 1rem;
      font-weight: 600;
      border: 2px solid #0f0;
      background: transparent;
      color: #0f0;
      border-radius: 999px;
      cursor: pointer;
      transition: 0.2s ease;
    }

    .launch-btn:hover {
      background: #0f0;
      color: #000;
    }

    #toast-container {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      z-index: 9999;
    }

    .toast {
      display: flex;
      align-items: center;
      gap: 0.9rem;
      padding: 1rem 1.4rem;
      border-radius: 10px;
      font-size: 0.95rem;
      max-width: 320px;
      background: #111;
      color: #0f0;
      border: 1.5px solid #0f0;
      text-shadow: 0 0 4px #0f0;
      box-shadow: 0 0 6px rgba(0, 255, 0, 0.15), 0 0 12px rgba(0, 255, 0, 0.1);
      animation: slide 0.6s ease-out both;
      position: relative;
      overflow: hidden;
    }

    .toast i {
      font-size: 1.3rem;
    }

    .toast-glow {
      position: absolute;
      inset: -20px;
      border-radius: 20px;
      background: radial-gradient(circle, rgba(0,255,0,0.25), transparent 70%);
      pointer-events: none;
      filter: blur(16px);
      z-index: -1;
    }

    @keyframes slide {
      0% {
        opacity: 0;
        transform: translateY(40px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

  <button class="launch-btn" onclick="showNeonToast()">
    <i class="fa-solid fa-bolt-lightning"></i> Let's Get Toasted
  </button>

  <div id="toast-container"></div>

  <script>
    function showNeonToast() {
      const container = document.getElementById("toast-container");
      const toast = document.createElement("div");
      toast.className = "toast";

      const icon = document.createElement("i");
      icon.className = "fa-solid fa-bolt-lightning";

      const text = document.createElement("span");
      text.textContent = "This is a Neon toast. Let's get toasted!";

      const glow = document.createElement("div");
      glow.className = "toast-glow";

      toast.appendChild(icon);
      toast.appendChild(text);
      toast.appendChild(glow);
      container.appendChild(toast);

      setTimeout(() => toast.remove(), 6000);
    }
  </script>

</body>
</html>
