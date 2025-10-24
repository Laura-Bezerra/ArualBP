// NAVBAR INDICADOR DESLIZANTE - ARUALBP
const links = document.querySelectorAll(".nav-links li a");
const indicator = document.querySelector(".nav-indicator");

function setIndicator(el) {
  const parent = el.parentElement;
  indicator.style.width = `${parent.offsetWidth}px`;
  indicator.style.left = `${parent.offsetLeft}px`;
}

links.forEach(link => {
  link.addEventListener("mouseenter", (e) => {
    setIndicator(e.target);
  });

  link.addEventListener("mouseleave", () => {
    const active = document.querySelector(".nav-links li.active a");
    if (active) setIndicator(active);
  });

  link.addEventListener("click", (e) => {
    document.querySelectorAll(".nav-links li").forEach(li => li.classList.remove("active"));
    e.target.parentElement.classList.add("active");
    setIndicator(e.target);
  });
});

window.addEventListener("load", () => {
  const active = document.querySelector(".nav-links li.active a");
  if (active) setIndicator(active);
});

const menuToggle = document.getElementById("menuToggle");
const navLinks = document.getElementById("navLinks");

menuToggle.addEventListener("click", () => {
  menuToggle.classList.toggle("active");
  navLinks.classList.toggle("open");
});
