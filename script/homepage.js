setInterval(createPetal, 500);

function createPetal() {
  const petal = document.createElement("div");
  petal.classList.add("petal");

  let startPosition = Math.random() * window.innerWidth;
  let duration = Math.random() * 5 + 5;
  let size = Math.random() * 10 + 10;

  petal.style.left = `${startPosition}px`;
  petal.style.animationDuration = `${duration}s`;
  petal.style.width = `${size}px`;
  petal.style.height = `${size}px`;

  document.body.appendChild(petal);

  setTimeout(() => {
    petal.remove();
  }, duration * 1000);
}
