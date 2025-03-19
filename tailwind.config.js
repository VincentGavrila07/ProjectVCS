/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      './src/**/*.{js,jsx,ts,tsx}',
    ],
    theme: {
      extend: {
        fontFamily: {
          montserrat: ['Montserrat', 'sans-serif'],
        },
        colors: {
          darkBrown: '#29251f',
          lightBrown: '#4a4843',
          softYellow: '#f0ece4',
          lightYellow: '#f7f7f5',
          brownFooter:'#4d443d',
          softKhaki: '#aba39a',
          fontSoft: '#f6efed',
        },
      },
    },
    plugins: [],
  }
  
  