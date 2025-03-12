const navLink = document.querySelectorAll(".nav-link");

navLink.forEach((a) => {
  const href = a.getAttribute("href").split("?")[0]; // Split la partie search du href pour n'avoir que le pathname
  if (href === window.location.pathname) {
    a.classList.add("active-page");
    a.setAttribute("aria-current", "page"); // Accessibilité
  }
});
