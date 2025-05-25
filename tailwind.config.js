/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.php",         // tes vues
    "./public/**/*.php",      // au cas où
    "./*.php"                 // racine
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
