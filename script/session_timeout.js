let timeout;
/*
 * Fonction pour réinitialiser le minuteur d'inactivité
 * Si l'utilisateur est inactif pendant 20 minutes, il sera redirigé vers la page d'accueil
 */
function resetTimer() {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    window.location.href = "../controller/logout_controller.php";
  }, 20 * 60000); // 20 minutes
}
["click", "mousemove", "keypress", "scroll"].forEach((e) =>
  window.addEventListener(e, resetTimer)
);
resetTimer();
