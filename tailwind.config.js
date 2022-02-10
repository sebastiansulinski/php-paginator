module.exports = {
  content: [
    './resources/**/*.{vue,js}',
    './public/index.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ]
}
