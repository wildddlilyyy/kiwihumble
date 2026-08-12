import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;

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

function mountParallax() {
  const items = [...document.querySelectorAll("[data-parallax]")];
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

  if (!items.length || reducedMotion.matches) {
    return;
  }

  let frame = null;

  const render = () => {
    frame = null;

    items.forEach((item) => {
      const bounds = item.getBoundingClientRect();
      const speed = Number(item.dataset.parallax || 0);
      const distanceFromCenter = bounds.top + bounds.height / 2 - window.innerHeight / 2;
      const offset = distanceFromCenter * speed;

      item.style.transform = `translate3d(0, ${offset.toFixed(2)}px, 0)`;
    });
  };

  const requestRender = () => {
    if (frame === null) {
      frame = window.requestAnimationFrame(render);
    }
  };

  render();
  window.addEventListener("scroll", requestRender, { passive: true });
  window.addEventListener("resize", requestRender, { passive: true });
}

mountParallax();

document.querySelectorAll("[data-password-toggle]").forEach((button) => {
  const input = document.querySelector(button.dataset.passwordToggle);

  if (!input) {
    return;
  }

  button.addEventListener("click", () => {
    const isHidden = input.type === "password";
    input.type = isHidden ? "text" : "password";
    button.textContent = isHidden ? "Hide" : "Show";
    button.setAttribute("aria-pressed", String(isHidden));
  });
});

document.querySelectorAll("[data-remember-login-form]").forEach((form) => {
  const input = form.querySelector("[data-remember-login-input]");
  const checkbox = form.querySelector("[data-remember-login-checkbox]");
  const key = `kiwi:${form.dataset.rememberLoginForm}:login`;

  if (!input || !checkbox) {
    return;
  }

  const rememberedValue = window.localStorage.getItem(key);

  if (rememberedValue && !input.value) {
    input.value = rememberedValue;
    checkbox.checked = true;
  }

  form.addEventListener("submit", () => {
    if (checkbox.checked) {
      window.localStorage.setItem(key, input.value);
      return;
    }

    window.localStorage.removeItem(key);
  });
});

window.classShirtOrderForm = function classShirtOrderForm(config) {
  return {
    items: (config.items ?? []).map((item) => ({
      category: item.category ?? "child",
      size: item.size ?? "#6",
      quantity: Number(item.quantity ?? 1),
    })),
    submittedAt: config.submittedAt,
    status: "",
    error: "",
    isSaving: false,
    sizeOptions: {
      child: ["#6", "#8", "#10"],
      adult: ["XS", "S", "M", "L", "XL", "2L", "3L", "5L"],
    },
    addItem() {
      this.items.push({ category: "child", size: "#6", quantity: 1 });
      this.status = "";
      this.error = "";
    },
    removeItem(index) {
      this.items.splice(index, 1);
      this.status = "";
      this.error = "";
    },
    normalizeSize(item) {
      if (!this.sizeOptions[item.category].includes(item.size)) {
        item.size = this.sizeOptions[item.category][0];
      }
    },
    totalQuantity() {
      return this.items.reduce((total, item) => total + Number(item.quantity || 0), 0);
    },
    async submit() {
      this.status = "";
      this.error = "";

      if (this.items.length === 0) {
        this.error = "請先新增至少一個班服品項。";
        return;
      }

      this.isSaving = true;

      try {
        const response = await fetch(config.storeUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": config.csrfToken,
          },
          body: JSON.stringify({ items: this.items }),
        });

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || "班服訂單送出失敗。");
        }

        this.items = data.items;
        this.submittedAt = data.submitted_at;
        this.status = data.status;
      } catch (error) {
        this.error = error.message;
      } finally {
        this.isSaving = false;
      }
    },
  };
};

Alpine.start();
