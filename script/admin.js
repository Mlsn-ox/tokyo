loadStats();

function loadStats() {
  fetch("../controller/get_stats.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
    });
}
