let timeout;
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