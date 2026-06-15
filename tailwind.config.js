import forms from "@tailwindcss/forms";

export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./app/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        kiwi: {
          blue: "#182d5b",
          ink: "#162e5c",
          cream: "#fff7df",
          gold: "#f0a624",
          orange: "#d86b1b",
          green: "#459e3a",
          brown: "#572a14",
        },
      },
      fontFamily: {
        sans: ["Nunito", "ui-sans-serif", "system-ui", "sans-serif"],
        display: ["Baloo 2", "Nunito", "ui-sans-serif", "system-ui", "sans-serif"],
        hand: ["Patrick Hand", "cursive"],
      },
    },
  },
  plugins: [forms],
};
