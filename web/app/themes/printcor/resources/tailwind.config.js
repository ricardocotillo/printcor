module.exports = {
  content: [
    '../templates/**/*.twig'
  ],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {
      height: {
        'screen-1/2': '50vh'
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
