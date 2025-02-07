// Tooltip Bootstrap
document.addEventListener("DOMContentLoaded", function () {
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.forEach(function (input) {
    var tooltip = new bootstrap.Tooltip(input, { trigger: "manual" });
    input.addEventListener("focus", function () {
      tooltip.show();
    });
    input.addEventListener("blur", function () {
      tooltip.hide();
    });
  });
});
