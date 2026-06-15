import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

function pad(value) {
  return String(value).padStart(2, "0");
}

function mountCountdown(root) {
  const target = new Date(root.dataset.targetDate);
  const days = root.querySelector("[data-count-days]");
  const hours = root.querySelector("[data-count-hours]");
  const minutes = root.querySelector("[data-count-minutes]");
  const seconds = root.querySelector("[data-count-seconds]");

  function render() {
    const diff = Math.max(0, target.getTime() - Date.now());
    const totalSeconds = Math.floor(diff / 1000);

    const remainingDays = Math.floor(totalSeconds / 86400);
    const remainingHours = Math.floor((totalSeconds % 86400) / 3600);
    const remainingMinutes = Math.floor((totalSeconds % 3600) / 60);
    const remainingSeconds = totalSeconds % 60;

    days.textContent = String(remainingDays).padStart(3, "0");
    hours.textContent = pad(remainingHours);
    minutes.textContent = pad(remainingMinutes);
    seconds.textContent = pad(remainingSeconds);
  }

  render();
  window.setInterval(render, 1000);
}

document.querySelectorAll("[data-countdown]").forEach(mountCountdown);
