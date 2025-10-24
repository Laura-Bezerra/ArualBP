// ===== Quadrados Interativos =====
const canvas = document.getElementById("bubbles");
const ctx = canvas.getContext("2d");
let squares = [];
let mouse = { x: 0, y: 0 };

function resizeCanvas() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

// Cria quadrados grandes e arredondados
for (let i = 0; i < 30; i++) {
  squares.push({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    size: Math.random() * 100 + 60,
    dx: (Math.random() - 0.5) * 0.3,
    dy: (Math.random() - 0.5) * 0.3,
    color: Math.random() > 0.5
      ? "rgba(145, 90, 211, 0.25)"  // lilás
      : "rgba(68, 18, 129, 0.25)"   // roxo translúcido
  });
}

function drawRoundedSquare(x, y, size, radius, color) {
  ctx.beginPath();
  ctx.moveTo(x + radius, y);
  ctx.lineTo(x + size - radius, y);
  ctx.quadraticCurveTo(x + size, y, x + size, y + radius);
  ctx.lineTo(x + size, y + size - radius);
  ctx.quadraticCurveTo(x + size, y + size, x + size - radius, y + size);
  ctx.lineTo(x + radius, y + size);
  ctx.quadraticCurveTo(x, y + size, x, y + size - radius);
  ctx.lineTo(x, y + radius);
  ctx.quadraticCurveTo(x, y, x + radius, y);
  ctx.closePath();
  ctx.fillStyle = color;
  ctx.fill();
}

function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  squares.forEach((s) => {
    drawRoundedSquare(s.x, s.y, s.size, s.size / 4, s.color);

    s.x += s.dx;
    s.y += s.dy;

    if (s.x < 0 || s.x + s.size > canvas.width) s.dx *= -1;
    if (s.y < 0 || s.y + s.size > canvas.height) s.dy *= -1;

    const distX = s.x + s.size / 2 - mouse.x;
    const distY = s.y + s.size / 2 - mouse.y;
    const distance = Math.sqrt(distX * distX + distY * distY);
    if (distance < 200) {
      s.x += distX / distance * 1.5;
      s.y += distY / distance * 1.5;
    }
  });

  requestAnimationFrame(animate);
}
animate();

window.addEventListener("mousemove", (e) => {
  mouse.x = e.x;
  mouse.y = e.y;
});

// ===== Alerta fade-out =====
window.addEventListener("DOMContentLoaded", () => {
  const alert = document.querySelector(".alert");
  if (alert) {
    setTimeout(() => {
      alert.classList.add("fade-out");
      setTimeout(() => alert.remove(), 600);
    }, 4000);
  }
});


// ===== Modal: Esqueci minha senha =====
function openForgotModal() {
  document.getElementById("forgotModal").classList.add("active");
}

function closeForgotModal() {
  document.getElementById("forgotModal").classList.remove("active");
}
