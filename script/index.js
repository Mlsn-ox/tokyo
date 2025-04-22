const resetBtn = document.getElementById("reset");
const input = document.querySelector("input[name='search']");
const select = document.querySelector("select");

resetBtn.addEventListener("click", function () {
    input.value = "";
    select.selectedIndex = 0;
})